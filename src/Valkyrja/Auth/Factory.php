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

namespace Valkyrja\Auth;

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
     * @param string $name   The adapter
     * @param array  $config The config
     *
     * @return Adapter
     */
    public function createAdapter(string $name, array $config): Adapter;

    /**
     * Create a repository by user entity name.
     *
     * @param Adapter $adapter The adapter
     * @param string  $name    The name
     * @param string  $user    The user
     * @param array   $config  The config
     *
     * @return Repository
     */
    public function createRepository(Adapter $adapter, string $name, string $user, array $config): Repository;

    /**
     * Create a gate by name.
     *
     * @param Repository $repository The repository
     * @param string     $name       The name
     *
     * @return Gate
     */
    public function createGate(Repository $repository, string $name): Gate;

    /**
     * Create a policy by name.
     *
     * @param Repository $repository The repository
     * @param string     $name       The name
     *
     * @return Policy
     */
    public function createPolicy(Repository $repository, string $name): Policy;
}
