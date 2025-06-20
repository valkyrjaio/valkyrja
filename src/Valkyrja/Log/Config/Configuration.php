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

namespace Valkyrja\Log\Config;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Log\Adapter\Contract\Adapter;
use Valkyrja\Log\Driver\Contract\Driver;

/**
 * Abstract Class Configuration.
 *
 * @author Melech Mizrachi
 */
abstract class Configuration extends ParentConfig
{
    /**
     * @param class-string<Adapter> $adapterClass
     * @param class-string<Driver>  $driverClass
     */
    public function __construct(
        public string $adapterClass,
        public string $driverClass = \Valkyrja\Log\Driver\Driver::class,
    ) {
    }
}
