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

namespace Valkyrja\Log;

use Throwable;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Log\Contract\Logger as Contract;
use Valkyrja\Log\Driver\Contract\Driver;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Factory\Contract\Factory;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 */
class Logger implements Contract
{
    /**
     * Logger constructor.
     */
    public function __construct(
        protected Factory $factory = new \Valkyrja\Log\Factory\Factory(),
        protected Config $config = new Config()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->config->defaultConfiguration;
        // The config to use
        $config = $this->config->configurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid configuration");
        // The driver to use
        $driverClass = $config->driverClass;
        // The adapter to use
        $adapterClass = $config->adapterClass;
        // The cache key to use
        $cacheKey = $name . $adapterClass;

        return $this->drivers[$cacheKey]
            ?? $this->factory->createDriver($driverClass, $adapterClass, $config);
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
    public function log(LogLevel $level, string $message, array $context = []): void
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
