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

namespace Valkyrja\Manager\Config;

use Valkyrja\Config\Config as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * The default configuration.
     *
     * @var string
     */
    public string $default;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The default driver.
     *
     * @var string
     */
    public string $driver;

    /**
     * The configurations.
     *
     * @var array[]
     */
    public array $configurations;
}
