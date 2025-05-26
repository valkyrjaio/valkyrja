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

namespace Valkyrja\Jwt\Config;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Jwt\Adapter\Contract\Adapter;
use Valkyrja\Jwt\Driver\Contract\Driver;
use Valkyrja\Jwt\Enum\Algorithm;

/**
 * Abstract Class Configuration.
 *
 * @author Melech Mizrachi
 */
abstract class Configuration extends ParentConfig
{
    /**
     * @param class-string<Adapter>|null $adapterClass
     * @param class-string<Driver>       $driverClass
     */
    public function __construct(
        public Algorithm $algorithm,
        public string|null $adapterClass = null,
        public string $driverClass = \Valkyrja\Jwt\Driver\Driver::class,
    ) {
    }
}
