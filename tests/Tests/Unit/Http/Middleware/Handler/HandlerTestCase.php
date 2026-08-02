<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Middleware\Handler;

use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * The Handler test case.
 */
abstract class HandlerTestCase extends TestCase
{
    protected Container $container;

    protected ServerRequest $request;

    protected Response $response;

    protected Route $route;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->container = new Container();

        $this->request  = new ServerRequest();
        $this->response = new Response();
        $this->route    = new Route(
            '/',
            'name',
            handler: static fn (): null => null,
        );
    }
}
