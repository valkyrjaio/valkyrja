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

namespace Valkyrja\Routing\Facades;

use Closure;
use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Routing\Collector as Contract;
use Valkyrja\Routing\Route;

/**
 * Class Collector.
 *
 * @author Melech Mizrachi
 *
 * @method static Route get(string $path, $handler, string $name = null)
 * @method static Route post(string $path, $handler, string $name = null)
 * @method static Route put(string $path, $handler, string $name = null)
 * @method static Route patch(string $path, $handler, string $name = null)
 * @method static Route delete(string $path, $handler, string $name = null)
 * @method static Route head(string $path, $handler, string $name = null)
 * @method static Route any(string $path, $handler, string $name = null)
 * @method static Route redirect(string $path, string $to, array $methods = null, string $name = null)
 * @method static Contract withPath(string $path)
 * @method static Contract withController(string $controller)
 * @method static Contract withName(string $name)
 * @method static Contract withMiddleware(array $middleware)
 * @method static Contract withSecure(bool $secure = true)
 * @method static Contract group(Closure $group)
 */
class Collector extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
