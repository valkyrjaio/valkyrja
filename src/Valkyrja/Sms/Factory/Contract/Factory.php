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

namespace Valkyrja\Sms\Factory\Contract;

use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Config\Configuration;
use Valkyrja\Sms\Config\MessageConfiguration;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Interface Factory.
 *
 * @author Melech Mizrachi
 */
interface Factory
{
    /**
     * Create a driver.
     *
     * @template Driver of Driver
     *
     * @param class-string<Driver>  $name    The driver
     * @param class-string<Adapter> $adapter The adapter
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, Configuration $config): Driver;

    /**
     * Create an adapter.
     *
     * @template Adapter of Adapter
     *
     * @param class-string<Adapter> $name The adapter
     *
     * @return Adapter
     */
    public function createAdapter(string $name, Configuration $config): Adapter;

    /**
     * Create a new message.
     *
     * @template Message of Message
     *
     * @param class-string<Message>   $name The message
     * @param array<array-key, mixed> $data [optional] The data
     *
     * @return Message
     */
    public function createMessage(string $name, MessageConfiguration $config, array $data = []): Message;
}
