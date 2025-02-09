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

namespace Valkyrja\Broadcast\Contract;

use Valkyrja\Broadcast\Adapter\Contract\Adapter;
use Valkyrja\Broadcast\Driver\Contract\Driver;
use Valkyrja\Broadcast\Factory\Contract\Factory;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Manager\Contract\MessageManager as Manager;

/**
 * Interface Broadcast.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Adapter, Driver, Factory, Message>
 */
interface Broadcast extends Manager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(?string $name = null): Driver;

    /**
     * @inheritDoc
     *
     * @return Message
     */
    public function createMessage(?string $name = null, array $data = []): Message;
}
