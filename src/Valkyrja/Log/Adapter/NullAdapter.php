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

namespace Valkyrja\Log\Adapter;

use Throwable;
use Valkyrja\Log\Adapter\Contract\Adapter as Contract;
use Valkyrja\Log\Config\NullConfiguration;
use Valkyrja\Log\Enum\LogLevel;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
{
    /**
     * NullAdapter constructor.
     */
    public function __construct(
        protected NullConfiguration $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function log(LogLevel $level, string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
    }
}
