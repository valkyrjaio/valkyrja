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
use Valkyrja\Cli\Interaction\Message\Contract\Message;

/**
 * Interface Output.
 *
 * @author Melech Mizrachi
 */
interface Output
{
    /**
     * Get all messages, ordered by written then unwritten.
     *
     * @return Message[]
     */
    public function getMessages(): array;

    /**
     * Get all the written messages.
     *
     * @return Message[]
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
     * @return Message[]
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
     * @param Message ...$messages The messages
     *
     * @return static
     */
    public function withMessages(Message ...$messages): static;

    /**
     * Create a new Output with the specified additional messages.
     *
     * @param Message ...$messages The messages
     *
     * @return static
     */
    public function withAddedMessages(Message ...$messages): static;

    /**
     * Create a new Output with the specified additional message.
     *
     * @param Message $message The message to add
     *
     * @return static
     */
    public function withAddedMessage(Message $message): static;

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
