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

use Valkyrja\Dispatcher\Dispatch;

/**
 * Class Service.
 *
 * @author Melech Mizrachi
 */
class Service extends Dispatch
{
    /**
     * Whether this service is a singleton.
     *
     * @var bool
     */
    protected $singleton = false;

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
    public function isSingleton(): bool
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
    public function setSingleton(bool $singleton = true): self
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
}
