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

namespace Valkyrja\Tests\Functional;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Entry\App;
use Valkyrja\Http\Message\Factory\RequestFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Support\Directory;
use Valkyrja\Tests\ConfigClass;
use Valkyrja\Tests\EnvClass;

/**
 * Test case for functional tests.
 *
 * @author Melech Mizrachi
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Setup functional tests.
     *
     * @return void
     */
    protected function setUp(): void
    {
        App::directory(dir: __DIR__ . '/../..');

        $this->app = $app = App::app(
            env: EnvClass::class,
            config: ConfigClass::class
        );

        Directory::$BASE_PATH    = __DIR__ . '/../..';
        Directory::$STORAGE_PATH = 'tests/storage';

        $container = App::getContainer($app);

        // $handler = $container->getSingleton(RequestHandler::class);
        // $handler->run(RequestFactory::fromGlobals());

        $container->setSingleton(ServerRequest::class, RequestFactory::fromGlobals());
    }
}
