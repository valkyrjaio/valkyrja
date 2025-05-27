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

namespace Valkyrja\Config\Support;

use Valkyrja\Config\Config\ValkyrjaDataConfig;

/**
 * Abstract Class Provider.
 *
 * @author Melech Mizrachi
 */
abstract class DataProvider
{
    /**
     * Publish the provider.
     */
    abstract public static function publish(ValkyrjaDataConfig $config): void;
}
