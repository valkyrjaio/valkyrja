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

namespace Valkyrja\Log\Managers;

use Throwable;
use Valkyrja\Log\Config\Config;
use Valkyrja\Log\Driver;
use Valkyrja\Log\Factory;
use Valkyrja\Log\Logger as Contract;
use Valkyrja\Support\Manager\Managers\Manager;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 *
 * @property Factory $factory
 */
class Logger extends Manager implements Contract
{
    /**
     * Logger constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['loggers'];
    }

    /**
     * @inheritDoc
     */
    public function use(string $name = null): Driver
    {
        return parent::use($name);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
        $this->use()->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
        $this->use()->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
        $this->use()->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
        $this->use()->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
        $this->use()->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
        $this->use()->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
        $this->use()->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->use()->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $this->use()->log($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
        $this->use()->exception($exception, $message, $context);
    }
}
