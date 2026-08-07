<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Abstract;

use Override;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Tests\Abstract\TestCase as AbstractTestCase;

/**
 * Test case for functional tests.
 */
abstract class TestCase extends AbstractTestCase
{
    /**
     * The application.
     */
    protected ApplicationContract $app;

    /**
     * The config.
     */
    protected Config $config;

    /**
     * Setup functional tests.
     */
    #[Override]
    protected function setUp(): void
    {
        App::directory(dir: Directory::$basePath);

        $this->app = $app = App::app(
            $this->config = new Config(),
        );

        $container = $app->getContainer();

        $container->setSingleton(ServerRequestContract::class, RequestFactory::fromGlobals());
    }
}
