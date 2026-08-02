<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Application\Entry;

use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

class Http extends App
{
    /**
     * Run the http app.
     */
    public static function run(HttpConfigContract $config): void
    {
        $app = static::start(
            config: $config,
        );

        $container = $app->getContainer();

        self::bootstrapThrowableHandler($app, $container);

        $handler = $container->getSingleton(RequestHandlerContract::class);
        $request = static::getRequest();
        $handler->run($request);
    }

    /**
     * Get the request.
     */
    public static function getRequest(): ServerRequestContract
    {
        return RequestFactory::fromGlobals();
    }
}
