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
     * @param ExitCode|int $exitCode    The exit code
     * @param Message      ...$messages The messages
     *
     * @return Output
     */
    public function createOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): Output;

    /**
     * Create a new EmptyOutput.
     *
     * @param ExitCode|int $exitCode    The exit code
     * @param Message      ...$messages The messages
     *
     * @return EmptyOutput
     */
    public function createEmptyOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): EmptyOutput;

    /**
     * Create a new PlainOutput.
     *
     * @param ExitCode|int $exitCode    The exit code
     * @param Message      ...$messages The messages
     *
     * @return PlainOutput
     */
    public function createPlainOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): PlainOutput;

    /**
     * Create a new FileOutput.
     *
     * @param non-empty-string $filepath    The filepath
     * @param ExitCode|int     $exitCode    The exit code
     * @param Message          ...$messages The messages
     *
     * @return FileOutput
     */
    public function createFileOutput(
        string $filepath,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): FileOutput;

    /**
     * Create a new StreamOutput.
     *
     * @param resource     $stream      The stream
     * @param ExitCode|int $exitCode    The exit code
     * @param Message      ...$messages The messages
     *
     * @return StreamOutput
     */
    public function createStreamOutput(
        $stream,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): StreamOutput;
}
