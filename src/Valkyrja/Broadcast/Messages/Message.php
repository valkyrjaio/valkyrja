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
    protected ?array $data = null;

    /**
     * Get the channel to broadcast on.
     *
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Set the channel to broadcast on.
     *
     * @param string $channel The channel
     *
     * @return static
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get the event to broadcast.
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * Set the event to broadcast.
     *
     * @param string $event The event
     *
     * @return static
     */
    public function setEvent(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Set the data to broadcast.
     *
     * @param array|null $data [optional] The data
     *
     * @return static
     */
    public function setData(array $data = null): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the message to broadcast.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the message to broadcast.
     *
     * @param string $message The message
     *
     * @return static
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
