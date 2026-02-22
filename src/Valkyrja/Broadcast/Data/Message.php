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

namespace Valkyrja\Broadcast\Data;

use Override;
use Valkyrja\Broadcast\Data\Contract\MessageContract;

class Message implements MessageContract
{
    /**
     * @param non-empty-string        $channel The channel
     * @param non-empty-string        $event   The event
     * @param non-empty-string        $message The message
     * @param array<array-key, mixed> $data    The data
     */
    public function __construct(
        protected string $channel,
        protected string $event,
        protected string $message,
        protected array $data = []
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withChannel(string $channel): static
    {
        $new = clone $this;

        $new->channel = $channel;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withEvent(string $event): static
    {
        $new = clone $this;

        $new->event = $event;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withData(array $data): static
    {
        $new = clone $this;

        $new->data = $data;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withMessage(string $message): static
    {
        $new = clone $this;

        $new->message = $message;

        return $new;
    }
}
