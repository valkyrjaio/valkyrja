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

use Valkyrja\Broadcast\Broadcast;
use Valkyrja\Container\Container;
use Valkyrja\Mail\Mail;
use Valkyrja\Notification\NotifiableUser;
use Valkyrja\Notification\Notification;
use Valkyrja\Notification\Notifier as Contract;
use Valkyrja\SMS\SMS;

/**
 * Class Notifier.
 *
 * @author Melech Mizrachi
 */
class Notifier implements Contract
{
    /**
     * The broadcaster.
     *
     * @var Broadcast
     */
    protected Broadcast $broadcast;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The mail service.
     *
     * @var Mail
     */
    protected Mail $mail;

    /**
     * The SMS service.
     *
     * @var SMS
     */
    protected SMS $sms;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The mail recipients.
     *
     * @var array[]
     */
    protected array $mailRecipients = [];

    /**
     * The SMS recipients.
     *
     * @var array[]
     */
    protected array $smsRecipients = [];

    /**
     * The broadcast events.
     *
     * @var array[]
     */
    protected array $broadcastEvents = [];

    /**
     * Notifier constructor.
     *
     * @param Container $container   The container
     * @param Broadcast $broadcaster The broadcaster
     * @param Mail      $mail        The mail service
     * @param SMS       $sms         The sms service
     * @param array     $config      The config
     */
    public function __construct(Container $container, Broadcast $broadcaster, Mail $mail, SMS $sms, array $config)
    {
        $this->broadcast = $broadcaster;
        $this->container = $container;
        $this->config    = $config;
        $this->mail      = $mail;
        $this->sms       = $sms;
    }

    /**
     * Create a new notification
     *
     * @param string $name The notification name
     * @param array  $data [optional] The data to add to the notification
     *
     * @return Notification
     */
    public function createNotification(string $name, array $data = []): Notification
    {
        return $this->container->get($this->config['notifications'][$name] ?? $name, $data);
    }

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
        $this->mailRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

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
        $this->smsRecipients[] = [
            'to' => $phoneNumber,
        ];

        return $this;
    }

    /**
     * Add a broadcast event to send a notification to.
     *
     * @param string $event The event
     *
     * @return static
     */
    public function addBroadcastEvent(string $event): self
    {
        $this->broadcastEvents[] = [
            'event' => $event,
        ];

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
        $this->addBroadcastUserRecipient($user);

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
        // public function notify(string $notificationName, array $data = []): void
    {
        // $notification = $this->getNotification($notificationName, $data);

        if ($notification->shouldSendBroadcastMessage()) {
            $this->notifyByBroadcast($notification);
        }

        if ($notification->shouldSendMailMessage()) {
            $this->notifyByMail($notification);
        }

        if ($notification->shouldSendSmsMessage()) {
            $this->notifyBySms($notification);
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
        $this->broadcastEvents = [];
        $this->mailRecipients  = [];
        $this->smsRecipients   = [];
    }

    /**
     * Add a user as an broadcast recipient.
     *
     * @param NotifiableUser $user The user
     *
     * @return void
     */
    protected function addBroadcastUserRecipient(NotifiableUser $user): void
    {
        if ($user::hasSecretIdField()) {
            $this->broadcastEvents[] = [
                'event' => $user->{$user::getSecretIdField()},
                'user'  => $user,
            ];
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
        $this->mailRecipients[] = [
            'email' => $user->{$user::getEmailField()},
            'name'  => $user::hasNameField() ? $user->{$user::getNameField()} : '',
            'user'  => $user,
        ];
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
        if ($user::hasPhoneNumberField()) {
            $this->smsRecipients[] = [
                'to'   => $user->{$user::getPhoneNumberField()},
                'user' => $user,
            ];
        }
    }

    /**
     * Send a notification by broadcast.
     *
     * @param Notification $notification The notification
     *
     * @return void
     */
    protected function notifyByBroadcast(Notification $notification): void
    {
        $broadcast        = $this->broadcast;
        $broadcastAdapter = $broadcast->getAdapter($notification->getBroadcastAdapterName());
        $broadcastMessage = $notification->getBroadcastMessageName();

        foreach ($this->broadcastEvents as $broadcastEvent) {
            $message = $broadcast->createMessage($broadcastMessage);

            $message->setEvent($broadcastEvent['event']);
            $notification->broadcast($message);

            $broadcastAdapter->send($message);
        }
    }

    /**
     * Send a notification by mail.
     *
     * @param Notification $notification The notification
     *
     * @return void
     */
    protected function notifyByMail(Notification $notification): void
    {
        $mail        = $this->mail;
        $mailAdapter = $mail->useMailer($notification->getMailAdapterName());
        $mailMessage = $notification->getMailMessageName();

        foreach ($this->mailRecipients as $mailRecipient) {
            $message = $mail->createMessage($mailMessage);

            $message->addRecipient($mailRecipient['email'], $mailRecipient['name']);
            $notification->mail($message);

            $mailAdapter->send($message);
        }
    }

    /**
     * Send a notification by SMS.
     *
     * @param Notification $notification The notification
     *
     * @return void
     */
    protected function notifyBySms(Notification $notification): void
    {
        $sms        = $this->sms;
        $smsAdapter = $sms->useMessenger($notification->getSmsAdapterName());
        $smsMessage = $notification->getSmsMessageName();

        foreach ($this->smsRecipients as $smsRecipient) {
            $message = $sms->createMessage($smsMessage);

            $message->setTo($smsRecipient['to']);
            $notification->sms($message);

            $smsAdapter->send($message);
        }
    }
}
