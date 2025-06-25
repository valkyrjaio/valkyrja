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

namespace Valkyrja\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory as Contract;
use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Interaction\Output\Contract\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\Contract\FileOutput;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutput;
use Valkyrja\Cli\Interaction\Output\Contract\StreamOutput;

/**
 * Class OutputFactory.
 *
 * @author Melech Mizrachi
 */
class OutputFactory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createOutput(
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): Output {
        return new \Valkyrja\Cli\Interaction\Output\Output(
            $isInteractive,
            $isQuiet,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    public function createEmptyOutput(
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): EmptyOutput {
        return new \Valkyrja\Cli\Interaction\Output\EmptyOutput(
            $isInteractive,
            $isQuiet,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    public function createPlainOutput(
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): PlainOutput {
        return new \Valkyrja\Cli\Interaction\Output\PlainOutput(
            $isInteractive,
            $isQuiet,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    public function createFileOutput(
        string $filepath,
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): FileOutput {
        return new \Valkyrja\Cli\Interaction\Output\FileOutput(
            $filepath,
            $isInteractive,
            $isQuiet,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    public function createStreamOutput(
        $stream,
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): StreamOutput {
        return new \Valkyrja\Cli\Interaction\Output\StreamOutput(
            $stream,
            $isInteractive,
            $isQuiet,
            $exitCode,
            ...$messages
        );
    }
}
