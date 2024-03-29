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

namespace Valkyrja\Manager;

/**
 * Interface Manager.
 *
 * @author   Melech Mizrachi
 *
 * @template Driver
 * @template Factory
 */
interface Manager
{
    /**
     * Use a specific configuration.
     *
     * @param string|null $name The name
     *
     * @return Driver
     */
    public function use(string|null $name = null): Driver;

    /**
     * Get the loader.
     *
     * @return Factory
     */
    public function getFactory(): Factory;
}
