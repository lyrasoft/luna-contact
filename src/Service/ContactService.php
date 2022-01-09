<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Service;

use Lyrasoft\Contact\Entity\Contact;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Mailer\MailerInterface;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Runtime\Config;

/**
 * The ContactService class.
 */
class ContactService
{
    use TranslatorTrait;

    public function __construct(protected MailerInterface $mailer, protected Config $config)
    {
    }

    public function createReceiverMailMessage(Contact $contact, ?string $subject = null, ?string $layout = null): MailMessage
    {
        $subject ??= $this->trans(
            'contact.mail.receiver.' . $contact->getType() . '.subject',
            uid: $contact->getId(),
            name: $contact->getName()
        );

        $message = $this->mailer->createMessage($subject)
            ->renderBody(
                $layout ?? 'mail.contact-mail-receiver-' . $contact->getType(),
                [
                    'item' => $contact
                ]
            );

        $receivers = $this->config->getDeep('contact.receivers.' . $contact->getType());

        foreach ($receivers['cc'] ?? [] as $address) {
            $message->cc($address);
        }

        foreach ($receivers['bcc'] ?? [] as $address) {
            $message->bcc($address);
        }

        return $message;
    }

    public function createThanksMailMessage(Contact $contact, ?string $subject = null, ?string $layout = null): MailMessage
    {
        $subject ??= $this->trans(
            'contact.mail.thanks.' . $contact->getType() . '.subject',
        );

        $message = $this->mailer->createMessage($subject)
            ->renderBody(
                $layout ?? 'mail.contact-mail-thanks-' . $contact->getType(),
                [
                    'item' => $contact
                ]
            );

        if ($contact->getEmail()) {
            $message->to("{$contact->getName()} <{$contact->getEmail()}>");
        }

        return $message;
    }
}
