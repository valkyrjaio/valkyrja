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

namespace Valkyrja\Sms\Messenger;

use Override;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Sms\Data\Contract\MessageContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract as Contract;

/**
 * Class LogSms.
 *
 * @author Melech Mizrachi
 */
class LogMessenger implements Contract
{
    /**
     * LogSms constructor.
     */
    public function __construct(
        protected LoggerContract $logger,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function send(MessageContract $message): void
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
