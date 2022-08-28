<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Event;

use Lyrasoft\Contact\Entity\Contact;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Data\Collection;
use Windwalker\Event\AbstractEvent;

/**
 * The ContactBeforeSendEvent class.
 */
class ContactBeforeSendEvent extends AbstractEvent
{
    protected string $type = '';

    protected MailMessage $message;

    protected Contact $entity;

    /**
     * @var Collection|User[]
     */
    protected Collection $users;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return  static  Return self to support chaining.
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return MailMessage
     */
    public function getMessage(): MailMessage
    {
        return $this->message;
    }

    /**
     * @param MailMessage $message
     *
     * @return  static  Return self to support chaining.
     */
    public function setMessage(MailMessage $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Contact
     */
    public function getEntity(): Contact
    {
        return $this->entity;
    }

    /**
     * @param Contact $entity
     *
     * @return  static  Return self to support chaining.
     */
    public function setEntity(Contact $entity): static
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     *
     * @return  static  Return self to support chaining.
     */
    public function setUsers(Collection $users): static
    {
        $this->users = $users;

        return $this;
    }
}
