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

namespace Valkyrja\Sms\Adapter;

use Valkyrja\Log\Driver\Contract\Driver as Logger;
use Valkyrja\Sms\Adapter\Contract\LogAdapter as Contract;
use Valkyrja\Sms\Config\LogConfiguration;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 */
class LogAdapter implements Contract
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
