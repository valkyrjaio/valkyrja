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

namespace Valkyrja\Broadcast;

use JsonException;
use Override;
use Valkyrja\Broadcast\Contract\Broadcaster as Contract;
use Valkyrja\Broadcast\Data\Contract\Message;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class LogBroadcaster.
 *
 * @author Melech Mizrachi
 */
class LogBroadcaster implements Contract
{
    /**
     * LogBroadcaster constructor.
     */
    public function __construct(
        protected Logger $logger
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function send(Message $message): void
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
