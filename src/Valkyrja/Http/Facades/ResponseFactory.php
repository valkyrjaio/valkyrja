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

namespace Valkyrja\Http\Facades;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory as Contract;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 *
 * @method static Response createResponse(string $content = null, int $statusCode = null, array $headers = null)
 * @method static JsonResponse createJsonResponse(array $data = null, int $statusCode = null, array $headers = null)
 * @method static JsonResponse createJsonpResponse(string $callback, array $data = null, int $statusCode = null, array $headers = null)
 * @method static RedirectResponse createRedirectResponse(string $uri = null, int $statusCode = null, array $headers = null)
 * @method static RedirectResponse route(string $route, array $parameters = null, int $statusCode = null, array $headers = null)
 * @method static Response view(string $template, array $data = null, int $statusCode = null, array $headers = null)
 */
class ResponseFactory extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
