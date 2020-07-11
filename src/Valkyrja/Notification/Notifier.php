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

namespace Valkyrja\Notification;

/**
 * Interface Notifier.
 *
 * @author Melech Mizrachi
 */
interface Notifier
{
    /**
     * Add a mail recipient to send a notification to.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addMailRecipient(string $email, string $name = ''): self;

    /**
     * Add an SMS recipient to send a notification to.
     *
     * @param string $phoneNumber The phone number
     *
     * @return static
     */
    public function addSmsRecipient(string $phoneNumber): self;

    /**
     * Add a User recipient to send a notification to.
     *
     * @param NotifiableUser $user
     *
     * @return static
     */
    public function addUserRecipient(NotifiableUser $user): self;

    /**
     * Send a notification to recipients.
     *
     * @param Notification $notification The notification to send
     *
     * @return void
     */
    public function notify(Notification $notification): void;

    /**
     * Send a notification to a user.
     *
     * @param Notification   $notification The notification to send
     * @param NotifiableUser $user         The user to notify
     *
     * @return void
     */
    public function notifyUser(Notification $notification, NotifiableUser $user): void;

    /**
     * Send a notification to users.
     *
     * @param Notification     $notification The notification to send
     * @param NotifiableUser[] $users        The users to notify
     *
     * @return void
     */
    public function notifyUsers(Notification $notification, NotifiableUser ...$users): void;
}
