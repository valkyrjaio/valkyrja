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

namespace Valkyrja\Cli\Interaction\Output\Factory\Contract;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\EmptyOutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\FileOutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\StreamOutputContract;

interface OutputFactoryContract
{
    /**
     * Create a new Output.
     *
     * @param ExitCode|int    $exitCode    The exit code
     * @param MessageContract ...$messages The messages
     */
    public function createOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages
    ): OutputContract;

    /**
     * Create a new EmptyOutput.
     *
     * @param ExitCode|int    $exitCode    The exit code
     * @param MessageContract ...$messages The messages
     */
    public function createEmptyOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages
    ): EmptyOutputContract;

    /**
     * Create a new PlainOutput.
     *
     * @param ExitCode|int    $exitCode    The exit code
     * @param MessageContract ...$messages The messages
     */
    public function createPlainOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages
    ): PlainOutputContract;

    /**
     * Create a new FileOutput.
     *
     * @param non-empty-string $filepath    The filepath
     * @param ExitCode|int     $exitCode    The exit code
     * @param MessageContract  ...$messages The messages
     */
    public function createFileOutput(
        string $filepath,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages
    ): FileOutputContract;

    /**
     * Create a new StreamOutput.
     *
     * @param resource        $stream      The stream
     * @param ExitCode|int    $exitCode    The exit code
     * @param MessageContract ...$messages The messages
     */
    public function createStreamOutput(
        $stream,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages
    ): StreamOutputContract;
}
