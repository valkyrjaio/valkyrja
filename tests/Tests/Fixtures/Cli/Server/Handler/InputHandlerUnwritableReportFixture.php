<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Server\Handler;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Cli\Server\Handler\InputHandler;

/**
 * Testable InputHandler whose throwable report writes to a filepath that takes no write.
 */
final class InputHandlerUnwritableReportFixture extends InputHandler
{
    /** @var non-empty-string */
    public string $reportFilepath = 'unset';

    #[Override]
    protected function getOutputFromThrowable(InputContract $input, Throwable $throwable): OutputContract
    {
        return new FileOutput($this->reportFilepath)
            ->withMessages(new Message($throwable->getMessage()));
    }
}
