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

/**
 * Interface Broadcast.
 *
 * @author Melech Mizrachi
 */
interface Broadcast
{
    /**
     * Create a new message.
     *
     * @param string|null $name [optional] The name of the message
     *
     * @return Message
     */
    public function createMessage(string $name = null): Message;

    /**
     * Get an adapter by name.
     *
     * @param string|null $name [optional] The name of the adapter
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter;
}
