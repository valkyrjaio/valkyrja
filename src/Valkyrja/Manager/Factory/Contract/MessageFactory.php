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

namespace Valkyrja\Manager\Factory\Contract;

use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Message\Contract\Message;

/**
 * Interface MessageFactory.
 *
 * @author   Melech Mizrachi
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 * @template Message of Message
 *
 * @extends Factory<Adapter, Driver>
 */
interface MessageFactory extends Factory
{
    /**
     * Create a new message.
     *
     * @param class-string<Message>   $name   The message
     * @param array<array-key, mixed> $config The config
     * @param array<array-key, mixed> $data   [optional] The data
     *
     * @return Message
     */
    public function createMessage(string $name, array $config, array $data = []): Message;
}
