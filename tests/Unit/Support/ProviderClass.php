<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Support;

use Valkyrja\Contracts\Application;
use Valkyrja\Support\Provider;

/**
 * Class to use to test the provider.
 *
 * @author Melech Mizrachi
 */
class ProviderClass extends Provider
{
    /**
     * Publish the service provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
    }
}
