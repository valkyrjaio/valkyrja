<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Middleware\Handler;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;

/**
 * Class TestThrowableCaughtHandler.
 */
final class ThrowableCaughtHandlerFixture extends ThrowableCaughtHandler
{
    protected int $count = 0;

    /**
     * Get the count of calls.
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable): OutputContract
    {
        $this->count++;

        return parent::throwableCaught($input, $output, $throwable);
    }
}
