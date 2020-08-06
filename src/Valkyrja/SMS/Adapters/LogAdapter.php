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

namespace Valkyrja\SMS\Adapters;

use Valkyrja\Log\Adapter as Log;
use Valkyrja\Log\Logger;
use Valkyrja\SMS\Adapter;
use Valkyrja\SMS\Message;

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 */
class LogAdapter implements Adapter
{
    /**
     * The log adapter.
     *
     * @var Log
     */
    protected Log $log;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * LogAdapter constructor.
     *
     * @param Logger $logger The logger
     * @param array  $config The config
     */
    public function __construct(Logger $logger, array $config)
    {
        $this->config = $config;
        $this->log    = $logger->getAdapter($config['adapter']);
    }

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void
    {
        $this->log->info(static::class . ' Send');
        $this->log->info('From:');
        $this->log->info($message->getFrom());
        $this->log->info('To:');
        $this->log->info($message->getTo());
        $this->log->info('Text:');
        $this->log->info($message->getText());
    }
}
