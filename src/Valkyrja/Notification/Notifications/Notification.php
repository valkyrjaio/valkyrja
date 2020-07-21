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

namespace Valkyrja\Notification\Notifications;

use Valkyrja\Broadcast\Message as BroadcastMessage;
use Valkyrja\Mail\Message as MailMessage;
use Valkyrja\Notification\Notification as Contract;
use Valkyrja\SMS\Message as SMSMessage;

/**
 * Abstract Class Notification.
 *
 * @author Melech Mizrachi
 */
abstract class Notification implements Contract
{
    /**
     * The broadcast adapter to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static ?string $broadcastAdapter = null;

    /**
     * The broadcast message to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static ?string $broadcastMessage = null;

    /**
     * @var bool
     */
    protected static bool $shouldSendBroadcast = true;

    /**
     * The mail adapter to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static ?string $mailAdapter = null;

    /**
     * The mail message to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static ?string $mailMessage = null;

    /**
     * @var bool
     */
    protected static bool $shouldSendMail = true;

    /**
     * The SMS adapter to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static ?string $smsAdapter = null;

    /**
     * The SMS message to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static ?string $smsMessage = null;

    /**
     * @var bool
     */
    protected static bool $shouldSendSms = true;

    /**
     * Get the broadcast adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getBroadcastAdapterName(): ?string
    {
        return static::$broadcastAdapter;
    }

    /**
     * Get the broadcast message's name to use for this notification.
     *
     * @return string|null
     */
    public function getBroadcastMessageName(): ?string
    {
        return static::$broadcastMessage;
    }

    /**
     * Whether an broadcast message should be sent for this notification.
     *
     * @return bool
     */
    public function shouldSendBroadcastMessage(): bool
    {
        return static::$shouldSendBroadcast;
    }

    /**
     * Get the mail adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getMailAdapterName(): ?string
    {
        return static::$mailAdapter;
    }

    /**
     * Get the mail message's name to use for this notification.
     *
     * @return string|null
     */
    public function getMailMessageName(): ?string
    {
        return static::$mailMessage;
    }

    /**
     * Whether a mail message should be sent for this notification.
     *
     * @return bool
     */
    public function shouldSendMailMessage(): bool
    {
        return static::$shouldSendMail;
    }

    /**
     * Get the SMS adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getSmsAdapterName(): ?string
    {
        return static::$smsAdapter;
    }

    /**
     * Get the SMS message's name to use for this notification.
     *
     * @return string|null
     */
    public function getSmsMessageName(): ?string
    {
        return static::$smsMessage;
    }

    /**
     * Whether an SMS message should be sent for this notification.
     *
     * @return bool
     */
    public function shouldSendSmsMessage(): bool
    {
        return static::$shouldSendSms;
    }

    /**
     * Notify by broadcast.
     *
     * @param BroadcastMessage $broadcastMessage The broadcast message
     *
     * @return BroadcastMessage
     */
    public function broadcast(BroadcastMessage $broadcastMessage): BroadcastMessage
    {
        return $broadcastMessage;
    }

    /**
     * Notify by mail.
     *
     * @param MailMessage $mailMessage The mail message
     *
     * @return MailMessage
     */
    public function mail(MailMessage $mailMessage): MailMessage
    {
        return $mailMessage;
    }

    /**
     * Notify by SMS.
     *
     * @param SMSMessage $message The SMS message
     *
     * @return SMSMessage
     */
    public function sms(SMSMessage $message): SMSMessage
    {
        return $message;
    }
}
