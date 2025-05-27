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

namespace Valkyrja\Broadcast\Adapter;

use JsonException;
use Valkyrja\Broadcast\Config\LogConfiguration;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Log\Driver\Contract\Driver as Logger;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 */
class LogAdapter extends NullAdapter
{
    /**
     * LogAdapter constructor.
     */
    public function __construct(
        protected Logger $log,
        protected LogConfiguration $config
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function send(Message $message): void
    {
        $this->log->info(static::class . ' Send');
        $this->log->info('Channel:');
        $this->log->info($message->getChannel());
        $this->log->info('Event:');
        $this->log->info($message->getEvent());
        $this->log->info('Data:');
        $this->log->info(Arr::toString($message->getData() ?? []));
        $this->log->info('Message:');
        $this->log->info($message->getMessage());
    }
}
