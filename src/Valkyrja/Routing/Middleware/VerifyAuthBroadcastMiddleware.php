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

namespace Valkyrja\Routing\Middleware;

use JsonException;
use Valkyrja\Auth\Auth;
use Valkyrja\Auth\User;
use Valkyrja\Broadcast\Broadcast;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Support\Middleware;

/**
 * Class VerifyAuthBroadcastMiddleware.
 *
 * @author Melech Mizrachi
 */
class VerifyAuthBroadcastMiddleware extends Middleware
{
    /**
     * The broadcast message param value.
     *
     * @var string
     */
    protected static string $broadcastMessageParamName = 'broadcast_message';

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public static function before(Request $request): Request|Response
    {
        $container = self::$container;
        /** @var Auth $auth */
        $auth = $container->getSingleton(Auth::class);
        /** @var Broadcast $broadcaster */
        $broadcaster = $container->getSingleton(Broadcast::class);
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $container->getSingleton(ResponseFactory::class);
        // The broadcast message
        $broadcastMessage = static::getBroadCastMessageFromRequest($request);

        // Ensure a broadcast message exists
        if (! $broadcastMessage) {
            return static::getNoBroadcastMessageResponse($responseFactory);
        }

        // Ensure a user is logged in
        if (! $auth->isAuthenticated()) {
            return static::getNoAuthUserResponse($responseFactory);
        }

        // Ensure the logged in user can read the broadcast message
        if (! static::determineCanRead($auth->getUser(), $broadcaster, $broadcastMessage)) {
            return static::getCannotReadResponse($responseFactory);
        }

        return $request;
    }

    /**
     * Get the broadcast message from the request.
     *
     * @param Request $request The request
     *
     * @return string|null
     */
    protected static function getBroadCastMessageFromRequest(Request $request): ?string
    {
        // Here we default to pulling the broadcast message from the body to avoid having the message appear in
        // plain text in the query string for a normal GET request (either in logs, or elsewhere). Ideally
        // a developer should only require updating the `static::$broadcastMessageParamName` value
        // should they extend this middleware for their own purposes to ensure security.
        return $request->getParsedBodyParam(static::$broadcastMessageParamName);
    }

    /**
     * Get the response to send when a broadcast message is not found in the request.
     *
     * @param ResponseFactory $responseFactory The response factory
     *
     * @return Response
     */
    protected static function getNoBroadcastMessageResponse(ResponseFactory $responseFactory): Response
    {
        return $responseFactory->createResponse('No broadcast message found.', StatusCode::BAD_REQUEST);
    }

    /**
     * Get the response to send when a user is not logged in.
     *
     * @param ResponseFactory $responseFactory The response factory
     *
     * @return Response
     */
    protected static function getNoAuthUserResponse(ResponseFactory $responseFactory): Response
    {
        return $responseFactory->createResponse('No user logged in.', StatusCode::BAD_REQUEST);
    }

    /**
     * Determine if the logged in user can read a broadcast message.
     *
     * @param User      $user             The logged in user
     * @param Broadcast $broadcaster      The broadcaster
     * @param string    $broadcastMessage The broadcast messages
     *
     * @throws JsonException
     *
     * @return bool
     */
    protected static function determineCanRead(User $user, Broadcast $broadcaster, string $broadcastMessage): bool
    {
        $idField = $user::getIdField();

        return $broadcaster->use()->determineKeyValueMatch(
            $idField,
            $user->__get($idField),
            $broadcastMessage
        );
    }

    /**
     * Get the response to send when a broadcast message cannot be read by the logged in user.
     *
     * @param ResponseFactory $responseFactory The response factory
     *
     * @return Response
     */
    protected static function getCannotReadResponse(ResponseFactory $responseFactory): Response
    {
        return $responseFactory->createResponse('Logged in user cannot read this message.', StatusCode::BAD_REQUEST);
    }
}
