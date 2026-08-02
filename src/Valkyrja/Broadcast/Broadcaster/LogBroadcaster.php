<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Broadcast\Broadcaster;

use JsonException;
use Override;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class LogBroadcaster implements BroadcasterContract
{
    public function __construct(
        protected LoggerContract $logger
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function send(MessageContract $message): void
    {
        $this->logger->info(static::class . ' Send');
        $this->logger->info('Channel:');
        $this->logger->info($message->getChannel());
        $this->logger->info('Event:');
        $this->logger->info($message->getEvent());
        $this->logger->info('Data:');
        $this->logger->info(ArrayFactory::toString($message->getData()));
        $this->logger->info('Message:');
        $this->logger->info($message->getMessage());
    }
}
