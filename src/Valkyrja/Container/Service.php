<?php

declare(strict_types=1);

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
 * Interface Service.
 *
 * @author Melech Mizrachi
 */
interface Service extends Dispatch
{
    /**
     * Get whether this is a singleton.
     *
     * @return bool
     */
    public function isSingleton(): bool;

    /**
     * Set whether this is a singleton.
     *
     * @param bool $singleton Whether this is a singleton
     *
     * @return static
     */
    public function setSingleton(bool $singleton = true): self;

    /**
     * Get defaults.
     *
     * @return array|null
     */
    public function getDefaults(): ?array;

    /**
     * Set defaults.
     *
     * @param array $defaults The defaults.
     *
     * @return static
     */
    public function setDefaults(array $defaults = null): self;
}
