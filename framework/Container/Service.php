<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Class Service
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class Service extends Dispatch implements Annotation
{
    /**
     * The id to map to.
     *
     * @var string
     */
    protected $id;

    /**
     * Whether this service is an alias.
     *
     * @var bool
     */
    protected $alias;

    /**
     * Whether this service is a singleton.
     *
     * @var bool
     */
    protected $singleton;

    /**
     * Default arguments.
     *
     * @var array
     */
    protected $defaults;

    /**
     * Before dependencies.
     *
     * @description
     * Before the dependencies are retrieved from the container.
     *
     * @var \Valkyrja\Dispatcher\Dispatch
     */
    protected $beforeDependencies;

    /**
     * After dependencies.
     *
     * @description
     * After the dependencies are retrieved from the container
     * but before arguments passed or defaults are added.
     *
     * @var \Valkyrja\Dispatcher\Dispatch
     */
    protected $afterDependencies;

    /**
     * Before make.
     *
     * @description
     * Before the object is made.
     *
     * @var \Valkyrja\Dispatcher\Dispatch
     */
    protected $beforeMake;

    /**
     * @return string
     */
    public function getId():? string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this;
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get whether this is an alias.
     *
     * @return bool
     */
    public function isAlias():? bool
    {
        return $this->alias;
    }

    /**
     * Set whether this is an alias.
     *
     * @param bool $alias Whether this is an alias
     *
     * @return $this;
     */
    public function setAlias(bool $alias = null): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get whether this is a singleton.
     *
     * @return bool
     */
    public function isSingleton():? bool
    {
        return $this->singleton;
    }

    /**
     * Set whether this is a singleton.
     *
     * @param bool $singleton Whether this is a singleton
     *
     * @return $this;
     */
    public function setSingleton(bool $singleton = null): self
    {
        $this->singleton = $singleton;

        return $this;
    }

    /**
     * Get defaults.
     *
     * @return array
     */
    public function getDefaults():? array
    {
        return $this->defaults;
    }

    /**
     * Set defaults.
     *
     * @param array $defaults The defaults.
     *
     * @return $this;
     */
    public function setDefaults(array $defaults = null): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * Get before dependencies dispatch.
     *
     * @return \Valkyrja\Dispatcher\Dispatch
     */
    public function getBeforeDependencies():? Dispatch
    {
        return $this->beforeDependencies;
    }

    /**
     * Set before dependencies dispatch.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $beforeDependencies The dispatch
     *
     * @return $this;
     */
    public function setBeforeDependencies(Dispatch $beforeDependencies = null): self
    {
        $this->beforeDependencies = $beforeDependencies;

        return $this;
    }

    /**
     * Get after dependencies dispatch.
     *
     * @return \Valkyrja\Dispatcher\Dispatch
     */
    public function getAfterDependencies():? Dispatch
    {
        return $this->afterDependencies;
    }

    /**
     * Set after dependencies dispatch.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $afterDependencies The dispatch
     *
     * @return $this;
     */
    public function setAfterDependencies(Dispatch $afterDependencies = null): self
    {
        $this->afterDependencies = $afterDependencies;

        return $this;
    }

    /**
     * Get before make dispatch.
     *
     * @return \Valkyrja\Dispatcher\Dispatch
     */
    public function getBeforeMake():? Dispatch
    {
        return $this->beforeMake;
    }

    /**
     * Set after make dispatch.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $beforeMake The dispatch
     *
     * @return $this;
     */
    public function setBeforeMake(Dispatch $beforeMake = null): self
    {
        $this->beforeMake = $beforeMake;

        return $this;
    }
}
