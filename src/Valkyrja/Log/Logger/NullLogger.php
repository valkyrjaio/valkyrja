<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Log\Logger;

use Override;
use Stringable;
use Throwable;
use Valkyrja\Log\Logger\Abstract\Logger;

class NullLogger extends Logger
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function debug(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function info(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function notice(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function warning(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function error(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function critical(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function alert(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function emergency(string|Stringable $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwable(Throwable $throwable, string|Stringable $message, array $context = []): void
    {
    }
}
