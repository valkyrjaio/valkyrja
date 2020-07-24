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
     * The logger.
     *
     * @var Logger
     */
    protected Logger $logger;

    /**
     * LogAdapter constructor.
     *
     * @param Logger $logger The logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
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
        $this->logger->info(static::class . ' Send');
    }
}
