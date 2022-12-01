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

use Valkyrja\Support\Manager\MessageManager as Manager;

/**
 * Interface Broadcast.
 *
 * @author Melech Mizrachi
 * @extends Manager<Driver, Factory, Message>
 */
interface Broadcast extends Manager
{
    /**
     * @inheritDoc
     *
     * @param class-string<Driver>|null $name The name
     *
     * @return Driver
     */
    public function use(string $name = null): Driver;

    /**
     * @inheritDoc
     *
     * @param class-string<Message>|null $name [optional] The name of the message
     *
     * @return Message
     */
    public function createMessage(string $name = null, array $data = []): Message;
}
