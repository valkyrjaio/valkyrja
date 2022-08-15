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

namespace Valkyrja\Log\Drivers;

use Throwable;
use Valkyrja\Log\Adapter;
use Valkyrja\Log\Driver as Contract;
use Valkyrja\Support\Manager\Drivers\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
        $this->adapter->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
        $this->adapter->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
        $this->adapter->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
        $this->adapter->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
        $this->adapter->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
        $this->adapter->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
        $this->adapter->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->adapter->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $this->adapter->log($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
        $this->adapter->exception($exception, $message);
    }
}
