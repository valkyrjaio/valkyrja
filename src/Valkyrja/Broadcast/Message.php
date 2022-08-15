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

namespace Valkyrja\Broadcast;

use Valkyrja\Support\Manager\Message as Contract;

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message extends Contract
{
    /**
     * Get the channel to broadcast on.
     *
     * @return string
     */
    public function getChannel(): string;

    /**
     * Set the channel to broadcast on.
     *
     * @param string $channel The channel
     *
     * @return static
     */
    public function setChannel(string $channel): self;

    /**
     * Get the event to broadcast.
     *
     * @return string
     */
    public function getEvent(): string;

    /**
     * Set the event to broadcast.
     *
     * @param string $event The event
     *
     * @return static
     */
    public function setEvent(string $event): self;

    /**
     * Get the data to broadcast.
     *
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * Set the data to broadcast.
     *
     * @param array|null $data [optional] The data
     *
     * @return static
     */
    public function setData(array $data = null): self;

    /**
     * Get the message to broadcast.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Set the message to broadcast.
     *
     * @param string $message The message
     *
     * @return static
     */
    public function setMessage(string $message): self;
}
