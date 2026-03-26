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
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

class Http extends App
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function run(Config|HttpConfig $config, Env $env = new Env()): void
    {
        if (! $config instanceof HttpConfig) {
            throw new InvalidArgumentException('Config must be an instance of HttpConfig');
        }

        $app = static::start(
            env: $env,
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
    protected static function getRequest(): ServerRequestContract
    {
        return RequestFactory::fromGlobals();
    }
}
