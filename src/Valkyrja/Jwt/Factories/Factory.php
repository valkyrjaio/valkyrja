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

namespace Valkyrja\Jwt\Factories;

use Valkyrja\Jwt\Adapter;
use Valkyrja\Jwt\Driver;
use Valkyrja\Jwt\Factory as Contract;
use Valkyrja\Manager\Factories\Factory as ManagerFactory;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 */
class Factory extends ManagerFactory implements Contract
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
