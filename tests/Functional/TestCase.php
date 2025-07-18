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
     * The env.
     *
     * @var EnvClass
     */
    protected EnvClass $env;

    /**
     * Setup functional tests.
     *
     * @return void
     */
    protected function setUp(): void
    {
        App::directory(dir: EnvClass::APP_DIR);

        $this->app = $app = App::app(
            $this->env = new EnvClass()
        );

        Directory::$BASE_PATH = EnvClass::APP_DIR;

        $container = $app->getContainer();

        // $handler = $container->getSingleton(RequestHandler::class);
        // $handler->run(RequestFactory::fromGlobals());

        $container->setSingleton(ServerRequest::class, RequestFactory::fromGlobals());
    }
}
