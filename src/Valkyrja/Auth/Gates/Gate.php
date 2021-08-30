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

namespace Valkyrja\Auth\Gates;

use Valkyrja\Auth\Gate as Contract;
use Valkyrja\Auth\Policy;
use Valkyrja\Auth\Repository;
use Valkyrja\Container\Container;

/**
 * Class Gate.
 *
 * @author Melech Mizrachi
 */
class Gate implements Contract
{
    /**
     * The policies cache.
     *
     * @var Policy[]
     */
    protected static array $policiesCache = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The repository.
     *
     * @var Repository
     */
    protected Repository $repository;

    /**
     * The policies.
     *
     * @var array
     */
    protected array $policies = [];

    /**
     * The default policy.
     *
     * @var string
     */
    protected string $defaultPolicy;

    /**
     * Gate constructor.
     *
     * @param Container  $container  The container
     * @param Repository $repository The repository
     * @param array      $config     The config
     */
    public function __construct(Container $container, Repository $repository, array $config)
    {
        $this->container     = $container;
        $this->repository    = $repository;
        $this->defaultPolicy = $config['policy'];
        $this->policies      = $config['policies'];
    }

    /**
     * Before authorization check.
     *
     * @return bool|null
     */
    public function before(): ?bool
    {
        return null;
    }

    /**
     * After authorization check.
     *
     * @return bool|null
     */
    public function after(): ?bool
    {
        return null;
    }

    /**
     * Check if the authenticated user is authorized.
     *
     * @param string      $action The action to check if authorized for
     * @param string|null $policy [optional] The policy
     *
     * @return bool
     */
    public function isAuthorized(string $action, string $policy = null): bool
    {
        return $this->before()
            ?? $this->after()
            ?? $this->getPolicy($policy)->isAuthorized($action);
    }

    /**
     * Get a policy by name.
     *
     * @param string|null $name [optional] The policy name
     *
     * @return Policy
     */
    public function getPolicy(string $name = null): Policy
    {
        $name ??= $this->defaultPolicy;

        return static::$policiesCache[$name]
            ?? static::$policiesCache[$name] = $this->container->get(
                $this->policies[$name],
                [
                    $this->repository,
                ]
            );
    }
}
