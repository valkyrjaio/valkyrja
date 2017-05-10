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
 * Class Service.
 *
 *
 * @author  Melech Mizrachi
 */
class Service extends Dispatch implements Annotation
{
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
     * @return $this
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
     * @return $this
     */
    public function setDefaults(array $defaults = null): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * Get a service from properties.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Container\Service
     */
    public static function getService(array $properties): self
    {
        $service = new self();

        $service
            ->setId($properties['id'] ?? null)
            ->setClass($properties['class'] ?? null)
            ->setMethod($properties['method'] ?? null)
            ->setProperty($properties['property'] ?? null)
            ->setFunction($properties['function'] ?? null)
            ->setClosure($properties['closure'] ?? null)
            ->setMatches($properties['matches'] ?? null)
            ->setArguments($properties['arguments'] ?? null)
            ->setDependencies($properties['dependencies'] ?? null)
            ->setSingleton($properties['singleton'] ?? null)
            ->setDefaults($properties['defaults'] ?? null)
            ->setStatic($properties['static'] ?? null);

        return $service;
    }

    /**
     * Set the state of the service.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Container\Service
     */
    public static function __set_state(array $properties)
    {
        return static::getService($properties);
    }
}
