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

use Valkyrja\Support\ServiceProvider;

/**
 * Class to use to test the service provider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderClass extends ServiceProvider
{
    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
    }
}
