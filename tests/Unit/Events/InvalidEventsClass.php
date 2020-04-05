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

namespace Valkyrja\Tests\Unit\Events;

use Valkyrja\Application\Application;

/**
 * Invalid events class to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidEventsClass
{
    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * InvalidEventsClass constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Publish.
     *
     * @param Application $app
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->setEvents(new static($app));
    }
}
