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

namespace Valkyrja\Cli\Interaction\Output\Contract;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;

interface OutputContract
{
    /**
     * Get all messages, ordered by written then unwritten.
     *
     * @return MessageContract[]
     */
    public function getMessages(): array;

    /**
     * Get all the written messages.
     *
     * @return MessageContract[]
     */
    public function getWrittenMessages(): array;

    /**
     * Determine if there are written messages.
     *
     * @return bool
     */
    public function hasWrittenMessage(): bool;

    /**
     * Get all the unwritten messages.
     *
     * @return MessageContract[]
     */
    public function getUnwrittenMessages(): array;

    /**
     * Determine if there are unwritten messages.
     *
     * @return bool
     */
    public function hasUnwrittenMessage(): bool;

    /**
     * Create a new Output with the specified messages.
     *
     * @param MessageContract ...$messages The messages
     *
     * @return static
     */
    public function withMessages(MessageContract ...$messages): static;

    /**
     * Create a new Output with the specified additional messages.
     *
     * @param MessageContract ...$messages The messages
     *
     * @return static
     */
    public function withAddedMessages(MessageContract ...$messages): static;

    /**
     * Create a new Output with the specified additional message.
     *
     * @param MessageContract $message The message to add
     *
     * @return static
     */
    public function withAddedMessage(MessageContract $message): static;

    /**
     * Write all unwritten messages.
     *
     * @return static
     */
    public function writeMessages(): static;

    /**
     * Determine whether this output should be interactive.
     *
     * @return bool
     */
    public function isInteractive(): bool;

    /**
     * Create a new Output with the specified interactivity.
     *
     * @param bool $isInteractive The interactivity
     *
     * @return static
     */
    public function withIsInteractive(bool $isInteractive): static;

    /**
     * Determine whether this output should be quiet.
     *
     * @return bool
     */
    public function isQuiet(): bool;

    /**
     * Create a new Output with the specified quietness.
     *
     * @param bool $isQuiet The quietness
     *
     * @return static
     */
    public function withIsQuiet(bool $isQuiet): static;

    /**
     * Determine whether this output should be silent.
     *
     * @return bool
     */
    public function isSilent(): bool;

    /**
     * Create a new Output with the specified silentness.
     *
     * @param bool $isSilent The silentness
     *
     * @return static
     */
    public function withIsSilent(bool $isSilent): static;

    /**
     * Get the exit code.
     *
     * @return ExitCode|int
     */
    public function getExitCode(): ExitCode|int;

    /**
     * Create a new Output with the specified exit code.
     *
     * @param ExitCode|int $exitCode The exit code
     *
     * @return static
     */
    public function withExitCode(ExitCode|int $exitCode): static;
}
