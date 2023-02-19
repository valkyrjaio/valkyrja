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

namespace Valkyrja\Broadcast\Messages;

use Valkyrja\Broadcast\Message as Contract;

/**
 * Class Message.
 *
 * @author Melech Mizrachi
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
     * @var array|null
     */
    protected array|null $data = null;

    /**
     * @inheritDoc
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @inheritDoc
     */
    public function setChannel(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @inheritDoc
     */
    public function setEvent(string $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(array $data = null): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
