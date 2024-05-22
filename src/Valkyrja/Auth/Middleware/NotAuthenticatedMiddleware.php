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
use Valkyrja\Config\Constant\ConfigKeyPart;
use Valkyrja\Http\Constant\StatusCode;
use Valkyrja\Http\Request\Contract\ServerRequest;
use Valkyrja\Http\Response\Contract\Response;
use Valkyrja\Routing\Url;

/**
 * Class NotAuthenticatedMiddleware.
 *
 * @author Melech Mizrachi
 */
class NotAuthenticatedMiddleware extends AuthMiddleware
{
    /**
     * The error message to use.
     *
     * @var string
     */
    protected static string $errorMessage = 'Must not be logged in.';

    /**
     * @inheritDoc
     */
    public static function before(ServerRequest $request): ServerRequest|Response
    {
        $repository = static::getRepository();

        if ($repository->isAuthenticated()) {
            return static::getFailedResponse($request);
        }

        return $request;
    }

    /**
     * @inheritDoc
     */
    protected static function getFailedRegularResponse(): Response
    {
        $authenticateUrl = static::getConfig(ConfigKeyPart::NOT_AUTHENTICATE_URL);

        if ($authenticateUrl !== null && $authenticateUrl !== '') {
            return self::getResponseFactory()->createRedirectResponse(
                $authenticateUrl,
                StatusCode::FOUND
            );
        }

        /** @var Url $url */
        $url = self::getContainer()->getSingleton(Url::class);

        return self::getResponseFactory()->createRedirectResponse(
            $url->getUrl((string) static::getConfig(ConfigKeyPart::NOT_AUTHENTICATE_ROUTE, RouteName::DASHBOARD)),
            StatusCode::FOUND
        );
    }
}
