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

use JsonException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Valkyrja\Application\Valkyrja;
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
     * @var Valkyrja
     */
    protected Valkyrja $app;

    /**
     * Setup functional tests.
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function setUp(): void
    {
        Directory::$BASE_PATH      = __DIR__ . '/../..';
        Directory::$BOOTSTRAP_PATH = 'tests/bootstrap';
        Directory::$STORAGE_PATH   = 'tests/storage';

        Valkyrja::setEnv(EnvClass::class);

        $this->app = new Valkyrja(ConfigClass::class);
        $this->app->container()->setSingleton(ServerRequest::class, RequestFactory::fromGlobals());
    }
}
