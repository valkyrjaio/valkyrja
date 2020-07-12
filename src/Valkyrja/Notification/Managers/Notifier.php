<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Notification\Managers;

use Valkyrja\Notification\NotifiableUser;
use Valkyrja\Notification\Notification;
use Valkyrja\Notification\Notifier as Contract;

/**
 * Class Notifier.
 *
 * @author Melech Mizrachi
 */
class Notifier implements Contract
{
    /**
     * The mail recipients.
     *
     * @var string[]
     */
    protected array $mailRecipients = [];

    /**
     * The mail recipients' names.
     *
     * @var string[]
     */
    protected array $mailRecipientNames = [];

    /**
     * The SMS recipients.
     *
     * @var string[]
     */
    protected array $smsRecipients = [];

    /**
     * Add a mail recipient to send a notification to.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addMailRecipient(string $email, string $name = ''): self
    {
        $this->mailRecipients[]     = $email;
        $this->mailRecipientNames[] = $name;

        return $this;
    }

    /**
     * Add an SMS recipient to send a notification to.
     *
     * @param string $phoneNumber The phone number
     *
     * @return static
     */
    public function addSmsRecipient(string $phoneNumber): self
    {
        $this->smsRecipients[] = $phoneNumber;

        return $this;
    }

    /**
     * Add a User recipient to send a notification to.
     *
     * @param NotifiableUser $user
     *
     * @return static
     */
    public function addUserRecipient(NotifiableUser $user): self
    {
        $this->addSmsUserRecipient($user);
        $this->addMailUserRecipient($user);

        return $this;
    }

    /**
     * Send a notification.
     *
     * @param Notification $notification The notification to send
     *
     * @return void
     */
    public function notify(Notification $notification): void
    {
        foreach ($this->mailRecipients as $key => $mailTo) {
            $notification->mail($mailTo, $this->mailRecipientNames[$key]);
        }

        foreach ($this->smsRecipients as $smsTo) {
            $notification->sms($smsTo);
        }

        $this->resetRecipients();
    }

    /**
     * Send a notification to a user.
     *
     * @param Notification   $notification The notification to send
     * @param NotifiableUser $user         The user to notify
     *
     * @return void
     */
    public function notifyUser(Notification $notification, NotifiableUser $user): void
    {
        $this->addUserRecipient($user);
        $this->notify($notification);
    }

    /**
     * Send a notification to users.
     *
     * @param Notification     $notification The notification to send
     * @param NotifiableUser[] $users        The users to notify
     *
     * @return void
     */
    public function notifyUsers(Notification $notification, NotifiableUser ...$users): void
    {
        foreach ($users as $user) {
            $this->addUserRecipient($user);
        }

        $this->notify($notification);
    }

    /**
     * Reset all the recipient arrays.
     *
     * @return void
     */
    protected function resetRecipients(): void
    {
        $this->mailRecipients     = [];
        $this->mailRecipientNames = [];
        $this->smsRecipients      = [];
    }

    /**
     * Add a user as an SMS recipient.
     *
     * @param NotifiableUser $user The user
     *
     * @return void
     */
    protected function addSmsUserRecipient(NotifiableUser $user): void
    {
        if ($user::hasPhoneNumberField() && $phoneNumber = $user->getPhoneNumberFieldValue()) {
            $this->addSmsRecipient($phoneNumber);
        }
    }

    /**
     * Add a user as a mail recipient.
     *
     * @param NotifiableUser $user The user
     *
     * @return void
     */
    protected function addMailUserRecipient(NotifiableUser $user): void
    {
        if ($email = $user->getEmailFieldValue()) {
            $this->addMailRecipient(
                $email,
                $user::hasNameField()
                    ? $user->getNameFieldValue()
                    : ''
            );
        }
    }
}
