<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Sms\Messenger;

use Override;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Sms\Data\Contract\MessageContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;

class LogMessenger implements MessengerContract
{
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
