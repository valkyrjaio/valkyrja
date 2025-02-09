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

namespace Valkyrja\Manager\Contract;

use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Factory\Contract\Factory;
use Valkyrja\Manager\Message\Contract\Message;

/**
 * Interface MessageManager.
 *
 * @author   Melech Mizrachi
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 * @template Factory of Factory
 * @template Message of Message
 *
 * @extends Manager<Adapter, Driver, Factory>
 */
interface MessageManager extends Manager
{
    /**
     * Create a new message.
     *
     * @param string|null             $name [optional] The name of the message
     * @param array<array-key, mixed> $data [optional] The data
     *
     * @return Message
     */
    public function createMessage(?string $name = null, array $data = []): Message;
}
