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

namespace Valkyrja\Broadcast\Broadcaster;

use JsonException;
use Override;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Type\BuiltIn\Support\Arr;

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
        $this->logger->info(Arr::toString($message->getData() ?? []));
        $this->logger->info('Message:');
        $this->logger->info($message->getMessage());
    }
}
