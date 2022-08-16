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

namespace Valkyrja\Crypt\Factories;

use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Driver;
use Valkyrja\Crypt\Factory as Contract;
use Valkyrja\Support\Manager\Factories\SimpleFactory as Factory;

/**
 * Class SimpleFactory.
 *
 * @author Melech Mizrachi
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
}
