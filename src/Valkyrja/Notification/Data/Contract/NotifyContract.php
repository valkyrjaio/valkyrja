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

namespace Valkyrja\Notification\Data\Contract;

use Valkyrja\Broadcast\Data\Contract\MessageContract as BroadcastMessage;
use Valkyrja\Mail\Data\Contract\MessageContract as MailMessage;
use Valkyrja\Sms\Data\Contract\MessageContract as SmsMessage;

interface NotifyContract
{
    /**
     * Get the broadcast adapter's name to use for this notification.
     */
    public function getBroadcastAdapterName(): string|null;

    /**
     * Get the broadcast message's name to use for this notification.
     */
    public function getBroadcastMessageName(): string|null;

    /**
     * Whether an broadcast message should be sent for this notification.
     */
    public function shouldSendBroadcastMessage(): bool;

    /**
     * Get the mail adapter's name to use for this notification.
     */
    public function getMailAdapterName(): string|null;

    /**
     * Get the mail message's name to use for this notification.
     */
    public function getMailMessageName(): string|null;

    /**
     * Whether a mail message should be sent for this notification.
     */
    public function shouldSendMailMessage(): bool;

    /**
     * Get the SMS adapter's name to use for this notification.
     */
    public function getSmsAdapterName(): string|null;

    /**
     * Get the SMS message's name to use for this notification.
     */
    public function getSmsMessageName(): string|null;

    /**
     * Whether an SMS message should be sent for this notification.
     */
    public function shouldSendSmsMessage(): bool;

    /**
     * Notify by broadcast.
     *
     * @param BroadcastMessage $broadcastMessage The broadcast message
     */
    public function broadcast(BroadcastMessage $broadcastMessage): BroadcastMessage;

    /**
     * Notify by mail.
     *
     * @param MailMessage $mailMessage The mail message
     */
    public function mail(MailMessage $mailMessage): MailMessage;

    /**
     * Notify by SMS.
     *
     * @param SmsMessage $message The SMS message
     */
    public function sms(SmsMessage $message): SmsMessage;
}
