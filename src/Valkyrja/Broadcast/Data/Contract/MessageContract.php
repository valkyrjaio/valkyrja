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

namespace Valkyrja\Broadcast\Data\Contract;

interface MessageContract
{
    /**
     * Get the channel to broadcast on.
     *
     * @return non-empty-string
     */
    public function getChannel(): string;

    /**
     * Set the channel to broadcast on.
     *
     * @param non-empty-string $channel The channel
     */
    public function withChannel(string $channel): static;

    /**
     * Get the event to broadcast.
     *
     * @return non-empty-string
     */
    public function getEvent(): string;

    /**
     * Set the event to broadcast.
     *
     * @param non-empty-string $event The event
     */
    public function withEvent(string $event): static;

    /**
     * Get the data to broadcast.
     *
     * @return array<array-key, mixed>
     */
    public function getData(): array;

    /**
     * Set the data to broadcast.
     *
     * @param array<array-key, mixed> $data The data
     */
    public function withData(array $data): static;

    /**
     * Get the message to broadcast.
     *
     * @return non-empty-string
     */
    public function getMessage(): string;

    /**
     * Set the message to broadcast.
     *
     * @param non-empty-string $message The message
     */
    public function withMessage(string $message): static;
}
