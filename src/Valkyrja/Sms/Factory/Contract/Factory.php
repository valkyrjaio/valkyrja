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

use Valkyrja\Manager\Factory\Contract\MessageFactory as Contract;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Interface Factory.
 *
 * @author Melech Mizrachi
 *
 * @extends Contract<Adapter, Driver, Message>
 */
interface Factory extends Contract
{
    /**
     * @inheritDoc
     *
     * @param class-string<Driver>  $name    The driver
     * @param class-string<Adapter> $adapter The adapter
     *
     * @return Driver
     */
    public function createDriver(string $name, string $adapter, array $config): Driver;

    /**
     * @inheritDoc
     *
     * @param class-string<Adapter> $name The adapter
     *
     * @return Adapter
     */
    public function createAdapter(string $name, array $config): Adapter;

    /**
     * @inheritDoc
     *
     * @param class-string<Message> $name The message
     *
     * @return Message
     */
    public function createMessage(string $name, array $config, array $data = []): Message;
}
