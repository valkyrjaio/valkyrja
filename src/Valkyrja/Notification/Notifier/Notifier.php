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

namespace Valkyrja\Notification\Notifier;

use Override;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Data\Message as BroadcastMessage;
use Valkyrja\Mail\Data\Message as MailMessage;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Notification\Data\Contract\NotifyContract;
use Valkyrja\Notification\Entity\Contract\NotifiableUserContract;
use Valkyrja\Notification\Factory\Contract\FactoryContract;
use Valkyrja\Notification\Notifier\Contract\NotifierContract;
use Valkyrja\Sms\Data\Message as SmsMessage;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Throwable\Exception\InvalidArgumentException;

use function is_string;

class Notifier implements NotifierContract
{
    /**
     * The mail recipients.
     *
     * @var array<int, array{email: string, name: string, user?: NotifiableUserContract}>
     */
    protected array $mailRecipients = [];

    /**
     * The SMS recipients.
     *
     * @var array<int, array{to: non-empty-string, from: non-empty-string, text: non-empty-string, user?: NotifiableUserContract}>
     */
    protected array $smsRecipients = [];

    /**
     * The broadcast events.
     *
     * @var array<int, array{event: string, user?: NotifiableUserContract}>
     */
    protected array $broadcastEvents = [];

    public function __construct(
        protected FactoryContract $factory,
        protected BroadcasterContract $broadcaster,
        protected MailerContract $mailer,
        protected MessengerContract $sms,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createNotification(string $name, array $data = []): NotifyContract
    {
        return $this->factory->createNotification($name, $data);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function addSmsRecipient(string $phoneNumber): static
    {
        // TODO: Figure this out
        $this->smsRecipients[] = [
            'to'   => $phoneNumber,
            'from' => 'us',
            'text' => 'text',
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function addUserRecipient(NotifiableUserContract $user): static
    {
        $this->addSmsUserRecipient($user);
        $this->addMailUserRecipient($user);
        $this->addBroadcastUserRecipient($user);

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function notify(NotifyContract $notify): void
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
    #[Override]
    public function notifyUser(NotifyContract $notify, NotifiableUserContract $user): void
    {
        $this->addUserRecipient($user);
        $this->notify($notify);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function notifyUsers(NotifyContract $notify, NotifiableUserContract ...$users): void
    {
        foreach ($users as $user) {
            $this->addUserRecipient($user);
        }

        $this->notify($notify);
    }

    /**
     * Reset all the recipient arrays.
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
     * @param NotifiableUserContract $user The user
     */
    protected function addBroadcastUserRecipient(NotifiableUserContract $user): void
    {
        /** @var mixed $secretId */
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
     * @param NotifiableUserContract $user The user
     */
    protected function addMailUserRecipient(NotifiableUserContract $user): void
    {
        /** @var mixed $email */
        $email = $user->__get($user::getEmailField());
        /** @var mixed $name */
        $name = $user::hasNameField()
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
     * @param NotifiableUserContract $user The user
     */
    protected function addSmsUserRecipient(NotifiableUserContract $user): void
    {
        /** @var mixed $phoneNumber */
        $phoneNumber = $user::hasPhoneNumberField()
            ? $user->__get($user::getPhoneNumberField())
            : null;

        if (is_string($phoneNumber) && $phoneNumber !== '') {
            $this->smsRecipients[] = [
                'to'   => $phoneNumber,
                'from' => 'us',
                'text' => 'test',
                'user' => $user,
            ];
        }
    }

    /**
     * Send a notification by broadcast.
     *
     * @param NotifyContract $notify The notification
     */
    protected function notifyByBroadcast(NotifyContract $notify): void
    {
        foreach ($this->broadcastEvents as $broadcastEvent) {
            $message = new BroadcastMessage();

            $message->setEvent($broadcastEvent['event']);
            $notify->broadcast($message);

            $this->broadcaster->send($message);
        }
    }

    /**
     * Send a notification by mail.
     *
     * @param NotifyContract $notify The notification
     */
    protected function notifyByMail(NotifyContract $notify): void
    {
        foreach ($this->mailRecipients as $mailRecipient) {
            $message = new MailMessage(
                $mailRecipient['email'],
                $mailRecipient['name']
            );

            $notify->mail($message);

            $this->mailer->send($message);
        }
    }

    /**
     * Send a notification by SMS.
     *
     * @param NotifyContract $notify The notification
     */
    protected function notifyBySms(NotifyContract $notify): void
    {
        foreach ($this->smsRecipients as $smsRecipient) {
            $message = new SmsMessage(
                $smsRecipient['to'],
                $smsRecipient['from'],
                $smsRecipient['text']
            );

            $notify->sms($message);

            $this->sms->send($message);
        }
    }
}
