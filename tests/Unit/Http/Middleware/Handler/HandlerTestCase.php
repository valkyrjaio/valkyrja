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

namespace Valkyrja\Tests\Unit\Http\Middleware\Handler;

use Valkyrja\Container\Manager\Container;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * The Handler test case.
 */
class HandlerTestCase extends TestCase
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );
    }
}
