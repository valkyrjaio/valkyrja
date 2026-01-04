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

namespace Valkyrja\Application\Entry;

use Override;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Http\Message\Factory\RequestFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

class Http extends App
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function run(string $dir, Env $env): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
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
    protected static function getRequest(): ServerRequestContract
    {
        return RequestFactory::fromGlobals();
    }
}
