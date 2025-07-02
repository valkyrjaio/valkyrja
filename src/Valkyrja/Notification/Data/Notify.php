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

namespace Valkyrja\Notification\Data;

use Valkyrja\Broadcast\Message\Contract\Message as BroadcastMessage;
use Valkyrja\Mail\Message\Contract\Message as MailMessage;
use Valkyrja\Notification\Data\Contract\Notify as Contract;
use Valkyrja\Sms\Data\Contract\Message as SmsMessage;

/**
 * Abstract Class Notify.
 *
 * @author Melech Mizrachi
 */
abstract class Notify implements Contract
{
    /**
     * The broadcast adapter to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static string|null $broadcastAdapter = null;

    /**
     * The broadcast message to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static string|null $broadcastMessage = null;

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
    protected static string|null $mailAdapter = null;

    /**
     * The mail message to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static string|null $mailMessage = null;

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
    protected static string|null $smsAdapter = null;

    /**
     * The SMS message to use for this notification.
     *  Null ensures the default from config is used.
     *
     * @var string|null
     */
    protected static string|null $smsMessage = null;

    /**
     * @var bool
     */
    protected static bool $shouldSendSms = true;

    /**
     * @inheritDoc
     */
    public function getBroadcastAdapterName(): string|null
    {
        return static::$broadcastAdapter;
    }

    /**
     * @inheritDoc
     */
    public function getBroadcastMessageName(): string|null
    {
        return static::$broadcastMessage;
    }

    /**
     * @inheritDoc
     */
    public function shouldSendBroadcastMessage(): bool
    {
        return static::$shouldSendBroadcast;
    }

    /**
     * @inheritDoc
     */
    public function getMailAdapterName(): string|null
    {
        return static::$mailAdapter;
    }

    /**
     * @inheritDoc
     */
    public function getMailMessageName(): string|null
    {
        return static::$mailMessage;
    }

    /**
     * @inheritDoc
     */
    public function shouldSendMailMessage(): bool
    {
        return static::$shouldSendMail;
    }

    /**
     * @inheritDoc
     */
    public function getSmsAdapterName(): string|null
    {
        return static::$smsAdapter;
    }

    /**
     * @inheritDoc
     */
    public function getSmsMessageName(): string|null
    {
        return static::$smsMessage;
    }

    /**
     * @inheritDoc
     */
    public function shouldSendSmsMessage(): bool
    {
        return static::$shouldSendSms;
    }

    /**
     * @inheritDoc
     */
    public function broadcast(BroadcastMessage $broadcastMessage): BroadcastMessage
    {
        return $broadcastMessage;
    }

    /**
     * @inheritDoc
     */
    public function mail(MailMessage $mailMessage): MailMessage
    {
        return $mailMessage;
    }

    /**
     * @inheritDoc
     */
    public function sms(SmsMessage $message): SmsMessage
    {
        return $message;
    }
}
