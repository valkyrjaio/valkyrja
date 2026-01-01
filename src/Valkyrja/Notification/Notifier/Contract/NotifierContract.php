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

namespace Valkyrja\Notification\Notifier\Contract;

use Valkyrja\Notification\Data\Contract\NotifyContract;
use Valkyrja\Notification\Entity\Contract\NotifiableUserContract;

/**
 * Interface NotificationContract.
 */
interface NotifierContract
{
    /**
     * Create a new notification.
     *
     * @param class-string<NotifyContract> $name The notification name
     * @param array<array-key, mixed>      $data [optional] The data to add to the notification
     *
     * @return NotifyContract
     */
    public function createNotification(string $name, array $data = []): NotifyContract;

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
     * @param non-empty-string $phoneNumber The phone number
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
     * @param NotifiableUserContract $user
     *
     * @return static
     */
    public function addUserRecipient(NotifiableUserContract $user): static;

    /**
     * Send a notification to recipients.
     *
     * @param NotifyContract $notify The notification to send
     *
     * @return void
     */
    public function notify(NotifyContract $notify): void;

    /**
     * Send a notification to a user.
     *
     * @param NotifyContract         $notify The notification to send
     * @param NotifiableUserContract $user   The user to notify
     *
     * @return void
     */
    public function notifyUser(NotifyContract $notify, NotifiableUserContract $user): void;

    /**
     * Send a notification to users.
     *
     * @param NotifyContract         $notify   The notification to send
     * @param NotifiableUserContract ...$users The users to notify
     *
     * @return void
     */
    public function notifyUsers(NotifyContract $notify, NotifiableUserContract ...$users): void;
}
