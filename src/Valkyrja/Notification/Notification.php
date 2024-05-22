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

use Valkyrja\Broadcast\Message\Contract\Message as BroadcastMessage;
use Valkyrja\Mail\Message as MailMessage;
use Valkyrja\Sms\Message as SMSMessage;

/**
 * Interface Notification.
 *
 * @author Melech Mizrachi
 */
interface Notification
{
    /**
     * Get the broadcast adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getBroadcastAdapterName(): string|null;

    /**
     * Get the broadcast message's name to use for this notification.
     *
     * @return string|null
     */
    public function getBroadcastMessageName(): string|null;

    /**
     * Whether an broadcast message should be sent for this notification.
     *
     * @return bool
     */
    public function shouldSendBroadcastMessage(): bool;

    /**
     * Get the mail adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getMailAdapterName(): string|null;

    /**
     * Get the mail message's name to use for this notification.
     *
     * @return string|null
     */
    public function getMailMessageName(): string|null;

    /**
     * Whether a mail message should be sent for this notification.
     *
     * @return bool
     */
    public function shouldSendMailMessage(): bool;

    /**
     * Get the SMS adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getSmsAdapterName(): string|null;

    /**
     * Get the SMS message's name to use for this notification.
     *
     * @return string|null
     */
    public function getSmsMessageName(): string|null;

    /**
     * Whether an SMS message should be sent for this notification.
     *
     * @return bool
     */
    public function shouldSendSmsMessage(): bool;

    /**
     * Notify by broadcast.
     *
     * @param BroadcastMessage $broadcastMessage The broadcast message
     *
     * @return BroadcastMessage
     */
    public function broadcast(BroadcastMessage $broadcastMessage): BroadcastMessage;

    /**
     * Notify by mail.
     *
     * @param MailMessage $mailMessage The mail message
     *
     * @return MailMessage
     */
    public function mail(MailMessage $mailMessage): MailMessage;

    /**
     * Notify by SMS.
     *
     * @param SMSMessage $message The SMS message
     *
     * @return SMSMessage
     */
    public function sms(SMSMessage $message): SMSMessage;
}
