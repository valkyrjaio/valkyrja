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

use Valkyrja\Manager\MessageManager as Manager;

/**
 * Interface Broadcast.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Driver, Factory, Message>
 */
interface Broadcast extends Manager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(string|null $name = null): Driver;

    /**
     * @inheritDoc
     *
     * @return Message
     */
    public function createMessage(string|null $name = null, array $data = []): Message;
}
