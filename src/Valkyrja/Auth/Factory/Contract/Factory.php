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

namespace Valkyrja\Auth\Factory\Contract;

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Repository\Contract\Repository;

/**
 * Interface Factory.
 *
 * @author Melech Mizrachi
 */
interface Factory
{
    /**
     * Create an adapter by name.
     *
     * @param class-string<Adapter> $name The adapter
     */
    public function createAdapter(string $name, Config $config): Adapter;

    /**
     * Create a repository by user entity name.
     *
     * @param class-string<Repository> $name The name
     * @param class-string<User>       $user The user
     */
    public function createRepository(Adapter $adapter, string $name, string $user, Config $config): Repository;

    /**
     * Create a gate by name.
     *
     * @param class-string<Gate> $name The name
     */
    public function createGate(Repository $repository, string $name): Gate;

    /**
     * Create a policy by name.
     *
     * @param class-string<Policy> $name The name
     */
    public function createPolicy(Repository $repository, string $name): Policy;
}
