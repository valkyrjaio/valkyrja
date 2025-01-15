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

namespace Valkyrja\Notification\Contract;

use Valkyrja\Notification\Data\Contract\Notify;
use Valkyrja\Notification\Entity\Contract\NotifiableUser;

/**
 * Interface Notification.
 *
 * @author Melech Mizrachi
 */
interface Notification
{
    /**
     * Create a new notification.
     *
     * @param string                  $name The notification name
     * @param array<array-key, mixed> $data [optional] The data to add to the notification
     *
     * @return Notify
     */
    public function createNotification(string $name, array $data = []): Notify;

    /**
     * Add a mail recipient to send a notification to.
     *
     * @param string $email The email
     * @param string $name  [optional] The name
     *
     * @return static
     */
    public function addMailRecipient(string $email, string $name = ''): static;

    /**
     * Add an SMS recipient to send a notification to.
     *
     * @param string $phoneNumber The phone number
     *
     * @return static
     */
    public function addSmsRecipient(string $phoneNumber): static;

    /**
     * Add a broadcast event to send a notification to.
     *
     * @param string $event The event
     *
     * @return static
     */
    public function addBroadcastEvent(string $event): static;

    /**
     * Add a User recipient to send a notification to.
     *
     * @param NotifiableUser $user
     *
     * @return static
     */
    public function addUserRecipient(NotifiableUser $user): static;

    /**
     * Send a notification to recipients.
     *
     * @param Notify $notify The notification to send
     *
     * @return void
     */
    public function notify(Notify $notify): void;

    /**
     * Send a notification to a user.
     *
     * @param Notify         $notify The notification to send
     * @param NotifiableUser $user   The user to notify
     *
     * @return void
     */
    public function notifyUser(Notify $notify, NotifiableUser $user): void;

    /**
     * Send a notification to users.
     *
     * @param Notify         $notify   The notification to send
     * @param NotifiableUser ...$users The users to notify
     *
     * @return void
     */
    public function notifyUsers(Notify $notify, NotifiableUser ...$users): void;
}
