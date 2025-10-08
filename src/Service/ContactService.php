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
}
