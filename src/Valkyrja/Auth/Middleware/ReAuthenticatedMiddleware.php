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

namespace Valkyrja\Auth\Middleware;

use Valkyrja\Auth\Constant\RouteName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Url\Contract\Url;

use function Valkyrja\container;
use function Valkyrja\responseFactory;

/**
 * Class ReAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ReAuthenticatedMiddleware extends AuthMiddleware
{
    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param ServerRequest $request The request
     *
     * @return ServerRequest|Response
     */
    public static function before(ServerRequest $request): ServerRequest|Response
    {
        if (static::getRepository()->isReAuthenticationRequired()) {
            return static::getFailedAuthenticationResponse($request);
        }

        return $request;
    }

    /**
     * Get the failed authentication response.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    protected static function getFailedAuthenticationResponse(ServerRequest $request): Response
    {
        $container       = container();
        $responseFactory = responseFactory();

        if ($request->isXmlHttpRequest()) {
            return $responseFactory->createJsonResponse([], StatusCode::LOCKED);
        }

        /** @var Url $url */
        $url = $container->getSingleton(Url::class);

        return $responseFactory->createRedirectResponse(
            $url->getUrl((string) static::getConfig('passwordConfirmRoute', RouteName::PASSWORD_CONFIRM))
        );
    }
}
