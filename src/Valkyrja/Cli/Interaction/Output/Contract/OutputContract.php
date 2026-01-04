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
use Valkyrja\Cli\Interaction\Writer\Contract\WriterContract;

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
     */
    public function hasUnwrittenMessage(): bool;

    /**
     * Create a new Output with the specified messages.
     *
     * @param MessageContract ...$messages The messages
     */
    public function withMessages(MessageContract ...$messages): static;

    /**
     * Create a new Output with the specified additional messages.
     *
     * @param MessageContract ...$messages The messages
     */
    public function withAddedMessages(MessageContract ...$messages): static;

    /**
     * Create a new Output with the specified additional message.
     *
     * @param MessageContract $message The message to add
     */
    public function withAddedMessage(MessageContract $message): static;

    /**
     * Write all unwritten messages.
     */
    public function writeMessages(): static;

    /**
     * Write a message.
     *
     * @param MessageContract $message The message
     */
    public function writeMessage(MessageContract $message): static;

    /**
     * Get the writers.
     *
     * @return WriterContract[]
     */
    public function getWriters(): array;

    /**
     * Create a new output with the specified writers.
     *
     * @param WriterContract ...$writers The writers
     */
    public function withWriters(WriterContract ...$writers): static;

    /**
     * Determine whether this output should be interactive.
     */
    public function isInteractive(): bool;

    /**
     * Create a new Output with the specified interactivity.
     *
     * @param bool $isInteractive The interactivity
     */
    public function withIsInteractive(bool $isInteractive): static;

    /**
     * Determine whether this output should be quiet.
     */
    public function isQuiet(): bool;

    /**
     * Create a new Output with the specified quietness.
     *
     * @param bool $isQuiet The quietness
     */
    public function withIsQuiet(bool $isQuiet): static;

    /**
     * Determine whether this output should be silent.
     */
    public function isSilent(): bool;

    /**
     * Create a new Output with the specified silentness.
     *
     * @param bool $isSilent The silentness
     */
    public function withIsSilent(bool $isSilent): static;

    /**
     * Get the exit code.
     */
    public function getExitCode(): ExitCode|int;

    /**
     * Create a new Output with the specified exit code.
     *
     * @param ExitCode|int $exitCode The exit code
     */
    public function withExitCode(ExitCode|int $exitCode): static;
}
