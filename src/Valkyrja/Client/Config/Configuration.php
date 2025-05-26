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

namespace Valkyrja\Client\Config;

use Valkyrja\Client\Adapter\Contract\Adapter;
use Valkyrja\Client\Driver\Contract\Driver;
use Valkyrja\Config\DataConfig as ParentConfig;

/**
 * Abstract Class Configuration.
 *
 * @author Melech Mizrachi
 */
abstract class Configuration extends ParentConfig
{
    /**
     * @param class-string<Adapter>|null $adapterClass
     * @param class-string<Driver>|null  $driverClass
     */
    public function __construct(
        public string|null $adapterClass = null,
        public string $driverClass = \Valkyrja\Client\Driver\Driver::class,
    ) {
    }
}
