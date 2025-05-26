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

namespace Valkyrja\Auth\Factory;

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Factory\Contract\Factory as Contract;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Repository\Contract\Repository;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 */
class Factory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, Config $config): Adapter
    {
        return new $name($config);
    }

    /**
     * @inheritDoc
     */
    public function createRepository(Adapter $adapter, string $name, string $user, Config $config): Repository
    {
        return new $name($adapter, $user, $config);
    }

    /**
     * @inheritDoc
     */
    public function createGate(Repository $repository, string $name): Gate
    {
        return new $name($repository);
    }

    /**
     * @inheritDoc
     */
    public function createPolicy(Repository $repository, string $name): Policy
    {
        return new $name($repository);
    }
}
