<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 *
 * @method static \Valkyrja\Http\Response make(string $content = null, int $statusCode = null, array $headers = null)
 * @method static \Valkyrja\Http\JsonResponse json(array $data = null, int $statusCode = null, array $headers = null)
 * @method static \Valkyrja\Http\JsonResponse jsonp(string $callback, array $data = null, int $statusCode = null, array $headers = null)
 * @method static \Valkyrja\Http\RedirectResponse redirect(string $uri = null, int $statusCode = null, array $headers = null)
 * @method static \Valkyrja\Http\RedirectResponse route(string $route, array $parameters = null, int $statusCode = null, array $headers = null)
 * @method static \Valkyrja\Http\Response view(string $template, array $data = null, int $statusCode = null, array $headers = null)
 */
class ResponseFactory extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->responseFactory();
    }
}