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

namespace Valkyrja\Sms\Factories;

use Valkyrja\Sms\Adapter;
use Valkyrja\Sms\Driver;
use Valkyrja\Sms\Factory as Contract;
use Valkyrja\Sms\Message;
use Valkyrja\Support\Manager\Factories\SimpleFactoryWithMessage as Factory;

/**
 * Class SimpleFactory.
 *
 * @author Melech Mizrachi
 * @extends Factory<Driver, Adapter, Message>
 */
class SimpleFactory extends Factory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createDriver(string $name, string $adapter, array $config): Driver
    {
        return parent::createDriver($name, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        return parent::createAdapter($name, $config);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name, array $config, array $data = []): Message
    {
        return parent::createMessage($name, $config, $data);
    }
}
