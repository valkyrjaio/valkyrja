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

namespace Valkyrja\Tests\Functional\Abstract;

use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Tests\Abstract\TestCase as AbstractTestCase;
use Valkyrja\Tests\EnvClass;

/**
 * Test case for functional tests.
 */
class TestCase extends AbstractTestCase
{
    /**
     * The application.
     *
     * @var ApplicationContract
     */
    protected ApplicationContract $app;

    /**
     * The env.
     *
     * @var EnvClass
     */
    protected EnvClass $env;

    /**
     * Setup functional tests.
     */
    protected function setUp(): void
    {
        App::directory(dir: EnvClass::APP_DIR);

        $this->app = $app = App::app(
            $this->env = new EnvClass()
        );

        Directory::$BASE_PATH = EnvClass::APP_DIR;

        $container = $app->getContainer();

        $container->setSingleton(ServerRequestContract::class, RequestFactory::fromGlobals());
    }
}
