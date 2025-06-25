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

namespace Valkyrja\Cli\Interaction\Factory\Contract;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Interaction\Output\Contract\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\Contract\FileOutput;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutput;
use Valkyrja\Cli\Interaction\Output\Contract\StreamOutput;

/**
 * Interface OutputFactory.
 *
 * @author Melech Mizrachi
 */
interface OutputFactory
{
    /**
     * Create a new Output.
     *
     * @param bool         $isInteractive Whether to allow interactivity
     * @param bool         $isQuiet       Whether to quiet output
     * @param ExitCode|int $exitCode      The exit code
     * @param Message      ...$messages   The messages
     *
     * @return Output
     */
    public function createOutput(
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): Output;

    /**
     * Create a new EmptyOutput.
     *
     * @param bool         $isInteractive Whether to allow interactivity
     * @param bool         $isQuiet       Whether to quiet output
     * @param ExitCode|int $exitCode      The exit code
     * @param Message      ...$messages   The messages
     *
     * @return EmptyOutput
     */
    public function createEmptyOutput(
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): EmptyOutput;

    /**
     * Create a new PlainOutput.
     *
     * @param bool         $isInteractive Whether to allow interactivity
     * @param bool         $isQuiet       Whether to quiet output
     * @param ExitCode|int $exitCode      The exit code
     * @param Message      ...$messages   The messages
     *
     * @return PlainOutput
     */
    public function createPlainOutput(
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): PlainOutput;

    /**
     * Create a new FileOutput.
     *
     * @param non-empty-string $filepath      The filepath
     * @param bool             $isInteractive Whether to allow interactivity
     * @param bool             $isQuiet       Whether to quiet output
     * @param ExitCode|int     $exitCode      The exit code
     * @param Message          ...$messages   The messages
     *
     * @return FileOutput
     */
    public function createFileOutput(
        string $filepath,
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): FileOutput;

    /**
     * Create a new StreamOutput.
     *
     * @param resource     $stream        The stream
     * @param bool         $isInteractive Whether to allow interactivity
     * @param bool         $isQuiet       Whether to quiet output
     * @param ExitCode|int $exitCode      The exit code
     * @param Message      ...$messages   The messages
     *
     * @return StreamOutput
     */
    public function createStreamOutput(
        $stream,
        bool $isInteractive = true,
        bool $isQuiet = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): StreamOutput;
}
