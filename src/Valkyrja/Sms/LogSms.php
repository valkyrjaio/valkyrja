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

namespace Valkyrja\Sms;

use Valkyrja\Log\Contract\Logger;
use Valkyrja\Sms\Contract\Sms as Contract;
use Valkyrja\Sms\Data\Contract\Message;

/**
 * Class LogSms.
 *
 * @author Melech Mizrachi
 */
class LogSms implements Contract
{
    /**
     * LogSms constructor.
     */
    public function __construct(
        protected Logger $logger,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->logger->info(static::class . ' Send');
        $this->logger->info('From:');
        $this->logger->info($message->getFrom());
        $this->logger->info('To:');
        $this->logger->info($message->getTo());
        $this->logger->info('Text:');
        $this->logger->info($message->getText());
    }
}
