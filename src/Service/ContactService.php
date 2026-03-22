<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Service;

use Lyrasoft\Contact\Entity\Contact;
use Symfony\Component\RateLimiter\Exception\RateLimitExceededException;
use Symfony\Component\RateLimiter\RateLimit;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\CacheStorage;
use Windwalker\Cache\CachePool;
use Windwalker\Cache\Serializer\PhpSerializer;
use Windwalker\Cache\Storage\FileStorage;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Mailer\MailerInterface;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Runtime\Config;
use Windwalker\Filesystem\Filesystem;

/**
 * The ContactService class.
 */
class ContactService
{
    use TranslatorTrait;

    public function __construct(
        protected ApplicationInterface $app,
        protected MailerInterface $mailer,
        protected Config $config
    ) {
    }

    public function createReceiverMailMessage(
        Contact $contact,
        ?string $subject = null,
        ?string $layout = null
    ): MailMessage {
        $subject ??= $this->trans(
            'contact.mail.receiver.' . $contact->type
            . '.subject',
            uid: $contact->id,
            name: $contact->name
        );

        $message = $this->mailer->createMessage($subject)
            ->renderBody(
                $layout ?? 'mail.contact-mail-receiver-' . $contact->type,
                [
                    'item' => $contact,
                ]
            );

        $receivers = $this->config->getDeep(
            'contact.receivers.' . $contact->type
        );

        foreach ($receivers['cc'] ?? [] as $address) {
            $message->cc($address);
        }

        foreach ($receivers['bcc'] ?? [] as $address) {
            $message->bcc($address);
        }

        return $message;
    }

    public function createThanksMailMessage(
        Contact $contact,
        ?string $subject = null,
        ?string $layout = null
    ): MailMessage {
        $subject ??= $this->trans(
            'contact.mail.thanks.' . $contact->type
            . '.subject',
        );

        $message = $this->mailer->createMessage($subject)
            ->renderBody(
                $layout ?? 'mail.contact-mail-thanks-' . $contact->type,
                [
                    'item' => $contact,
                ]
            );

        if (
            $contact->email
        ) {
            $message->to(
                "{$contact->name} <{$contact->email}>"
            );
        }

        return $message;
    }

    protected function getRateLimitConfig(string $type): array|\Closure
    {
        $config = $this->config->getDeep('contact.rate_limit.' . $type)
            ?? $this->config->getDeep('contact.rate_limit._default');

        if ($config instanceof \Closure) {
            return $config;
        }

        return array_merge(
            [
                'id' => $type,
                'policy' => 'fixed_window',
                'limit' => 10,
                'interval' => '1day',
            ],
            $config ?? []
        );
    }

    public function checkRateLimit(string $type, string $ip): RateLimit
    {
        $limiterFactory = $this->createRateLimiter($type);

        return $limiterFactory->create('contact--' . $ip)->consume(1);
    }

    public function rateLimitOrThrow(string $type, string $ip): void
    {
        $limit = $this->checkRateLimit($type, $ip);

        if ($limit->isAccepted()) {
            throw new RateLimitExceededException($limit, 429);
        }

        $this->clearFileCacheExpired();
    }

    public function createRateLimiter(string $type = 'main'): RateLimiterFactory
    {
        $config = $this->getRateLimitConfig($type);

        if ($config instanceof \Closure) {
            return $this->app->call($config);
        }

        return new RateLimiterFactory(
            $config,
            new CacheStorage(
                new CachePool(
                    $this->getFileStorage(),
                    new PhpSerializer(),
                    defaultTtl: 86400 * 30
                )
            )
        );
    }

    protected function getFileStorage(): FileStorage
    {
        return new FileStorage(
            WINDWALKER_CACHE . '/contact'
        );
    }

    public function clearFileCacheExpired(float $change = 0.01): void
    {
        $shouldClear = random_int(0, 999_999) / 1_000_000 < $change;

        if ($shouldClear) {
            return;
        }


        $storage = $this->getFileStorage();

        $files = Filesystem::files(WINDWALKER_CACHE . '/contact');

        foreach ($files as $file) {
            $data = (string) $file->read();

            preg_match(
                '#' . $storage::escapeRegex($storage->getOption('expiration_format')) . '#',
                $data,
                $matches
            );

            $expires = $matches[1];
            
            if ($expires < time()) {
                $file->deleteIfExists();
            }
        }
    }
}
