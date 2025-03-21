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

use Valkyrja\Broadcast\Message\Contract\Message as BroadcastMessage;
use Valkyrja\Mail\Message\Contract\Message as MailMessage;
use Valkyrja\Sms\Message\Contract\Message as SMSMessage;

/**
 * Interface Notify.
 *
 * @author Melech Mizrachi
 */
interface Notify
{
    /**
     * Get the broadcast adapter's name to use for this notification.
     *
     * @return string|null
     */
    public function getBroadcastAdapterName(): ?string;

    /**
     * Get the broadcast message's name to use for this notification.
     *
     * @return string|null
     */
    public function getBroadcastMessageName(): ?string;

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
    public function getMailAdapterName(): ?string;

    /**
     * Get the mail message's name to use for this notification.
     *
     * @return string|null
     */
    public function getMailMessageName(): ?string;

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
    public function getSmsAdapterName(): ?string;

    /**
     * Get the SMS message's name to use for this notification.
     *
     * @return string|null
     */
    public function getSmsMessageName(): ?string;

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
