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
use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Url\Contract\Url;

use function is_string;

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
        /** @var ResponseFactory $responseFactory */

        $authenticateUrl = static::getConfig(ConfigKeyPart::NOT_AUTHENTICATE_URL);

        if (is_string($authenticateUrl) && $authenticateUrl !== '') {
            return $responseFactory->createRedirectResponse(
                $authenticateUrl,
                StatusCode::FOUND
            );
        }

        $redirectRoute = static::getConfig(ConfigKeyPart::NOT_AUTHENTICATE_ROUTE, RouteName::DASHBOARD);

        if (! is_string($redirectRoute)) {
            throw new RuntimeException('notAuthenticateRoute in config should be a string');
        }

        /** @var Container $container */
        $url = $container->getSingleton(Url::class);

        return $responseFactory->createRedirectResponse(
            $url->getUrl($redirectRoute),
            StatusCode::FOUND
        );
    }
}
