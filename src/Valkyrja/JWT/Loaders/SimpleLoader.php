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

namespace Valkyrja\JWT\Loaders;

use Valkyrja\JWT\Adapter;
use Valkyrja\JWT\Driver;
use Valkyrja\JWT\Loader as Contract;
use Valkyrja\Support\Loader\Loaders\SimpleLoader as Loader;

/**
 * Class SimpleLoader.
 *
 * @author Melech Mizrachi
 */
class SimpleLoader extends Loader implements Contract
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
