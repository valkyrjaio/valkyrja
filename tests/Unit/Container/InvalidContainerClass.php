<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Container;

use Valkyrja\Application\Application;

/**
 * Invalid container class to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidContainerClass
{
    /**
     * The application.
     *
     * @var \Valkyrja\Application\Application
     */
    protected $app;

    /**
     * InvalidContainerClass constructor.
     *
     * @param \Valkyrja\Application\Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }
}
