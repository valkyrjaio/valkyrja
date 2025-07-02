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

use Valkyrja\Broadcast\Contract\Broadcast;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Mail\Contract\Mail;
use Valkyrja\Notification\Contract\Notification as Contract;
use Valkyrja\Notification\Data\Contract\Notify;
use Valkyrja\Notification\Entity\Contract\NotifiableUser;
use Valkyrja\Notification\Factory\Contract\Factory;
use Valkyrja\Sms\Contract\Sms;
use Valkyrja\Sms\Data\Message as SmsMessage;

use function is_string;

/**
 * Class Notification.
 *
 * @author Melech Mizrachi
 */
class Notification implements Contract
{
    /**
     * The mail recipients.
     *
     * @var array<int, array{email: string, name: string, user?: NotifiableUser}>
     */
    protected array $mailRecipients = [];

    /**
     * The SMS recipients.
     *
     * @var array<int, array{to: string, user?: NotifiableUser}>
     */
    protected array $smsRecipients = [];

    /**
     * The broadcast events.
     *
     * @var array<int, array{event: string, user?: NotifiableUser}>
     */
    protected array $broadcastEvents = [];

    /**
     * Notifier constructor.
     */
    public function __construct(
        protected Factory $factory,
        protected Broadcast $broadcast,
        protected Mail $mail,
        protected Sms $sms,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createNotification(string $name, array $data = []): Notify
    {
        return $this->factory->createNotification($name, $data);
    }

    /**
     * @inheritDoc
     */
    public function addMailRecipient(string $email, string $name = ''): static
    {
        $this->mailRecipients[] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addSmsRecipient(string $phoneNumber): static
    {
        $this->smsRecipients[] = [
            'to' => $phoneNumber,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addBroadcastEvent(string $event): static
    {
        $this->broadcastEvents[] = [
            'event' => $event,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addUserRecipient(NotifiableUser $user): static
    {
        $this->addSmsUserRecipient($user);
        $this->addMailUserRecipient($user);
        $this->addBroadcastUserRecipient($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function notify(Notify $notify): void
    // public function notify(string $notificationName, array $data = []): void
    {
        // $notification = $this->getNotification($notificationName, $data);

        if ($notify->shouldSendBroadcastMessage()) {
            $this->notifyByBroadcast($notify);
        }

        if ($notify->shouldSendMailMessage()) {
            $this->notifyByMail($notify);
        }

        if ($notify->shouldSendSmsMessage()) {
            $this->notifyBySms($notify);
        }

        $this->resetRecipients();
    }

    /**
     * @inheritDoc
     */
    public function notifyUser(Notify $notify, NotifiableUser $user): void
    {
        $this->addUserRecipient($user);
        $this->notify($notify);
    }

    /**
     * @inheritDoc
     */
    public function notifyUsers(Notify $notify, NotifiableUser ...$users): void
    {
        foreach ($users as $user) {
            $this->addUserRecipient($user);
        }

        $this->notify($notify);
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
        $secretId = $user::hasSecretIdField()
            ? $user->__get($user::getSecretIdField())
            : null;

        if (is_string($secretId)) {
            $this->broadcastEvents[] = [
                'event' => $secretId,
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
        $email = $user->__get($user::getEmailField());
        $name  = $user::hasNameField()
            ? $user->__get($user::getNameField())
            : '';

        if (! is_string($email)) {
            throw new InvalidArgumentException('Invalid email provided');
        }

        if (! is_string($name)) {
            throw new InvalidArgumentException('Invalid name provided');
        }

        $this->mailRecipients[] = [
            'email' => $email,
            'name'  => $name,
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
        $phoneNumber = $user::hasPhoneNumberField()
            ? $user->__get($user::getPhoneNumberField())
            : null;

        if (is_string($phoneNumber)) {
            $this->smsRecipients[] = [
                'to'   => $phoneNumber,
                'user' => $user,
            ];
        }
    }

    /**
     * Send a notification by broadcast.
     *
     * @param Notify $notify The notification
     *
     * @return void
     */
    protected function notifyByBroadcast(Notify $notify): void
    {
        $broadcast        = $this->broadcast;
        $broadcastAdapter = $broadcast->use($notify->getBroadcastAdapterName());
        $broadcastMessage = $notify->getBroadcastMessageName();

        foreach ($this->broadcastEvents as $broadcastEvent) {
            $message = $broadcast->createMessage($broadcastMessage);

            $message->setEvent($broadcastEvent['event']);
            $notify->broadcast($message);

            $broadcastAdapter->send($message);
        }
    }

    /**
     * Send a notification by mail.
     *
     * @param Notify $notify The notification
     *
     * @return void
     */
    protected function notifyByMail(Notify $notify): void
    {
        $mail        = $this->mail;
        $mailAdapter = $mail->use($notify->getMailAdapterName());
        $mailMessage = $notify->getMailMessageName();

        foreach ($this->mailRecipients as $mailRecipient) {
            $message = $mail->createMessage($mailMessage);

            $message->addRecipient($mailRecipient['email'], $mailRecipient['name']);
            $notify->mail($message);

            $mailAdapter->send($message);
        }
    }

    /**
     * Send a notification by SMS.
     *
     * @param Notify $notify The notification
     *
     * @return void
     */
    protected function notifyBySms(Notify $notify): void
    {
        foreach ($this->smsRecipients as $smsRecipient) {
            $message = new SmsMessage();

            $message->setTo($smsRecipient['to']);
            $notify->sms($message);

            $this->sms->send($message);
        }
    }
}
