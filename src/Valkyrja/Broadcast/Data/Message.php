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
use Valkyrja\Broadcast\Data\Contract\MessageContract as Contract;

/**
 * Class Message.
 */
class Message implements Contract
{
    /**
     * The channel to broadcast to.
     *
     * @var string
     */
    protected string $channel;

    /**
     * The event to broadcast to.
     *
     * @var string
     */
    protected string $event;

    /**
     * The message to broadcast.
     *
     * @var string
     */
    protected string $message;

    /**
     * The data to broadcast.
     *
     * @var array<array-key, mixed>|null
     */
    protected array|null $data = null;

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
    public function setChannel(string $channel): static
    {
        $this->channel = $channel;

        return $this;
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
    public function setEvent(string $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setData(array|null $data = null): static
    {
        $this->data = $data;

        return $this;
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
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
