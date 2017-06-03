<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotations;

use Valkyrja\Annotations\Annotation;

/**
 * Class Service.
 *
 * @author Melech Mizrachi
 */
class Service extends Annotation
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
     * @return void
     */
    public function setSingleton(bool $singleton = null): void
    {
        $this->singleton = $singleton;
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
     * @return void
     */
    public function setDefaults(array $defaults = null): void
    {
        $this->defaults = $defaults;
    }
}
