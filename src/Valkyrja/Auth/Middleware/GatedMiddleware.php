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

use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Data\Contract\ConstantDispatch;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;
use Valkyrja\Dispatcher\Data\Contract\PropertyDispatch;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Abstract Class GatedMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class GatedMiddleware extends AuthorizedMiddleware
{
    /**
     * The gate to check against.
     *
     * @var class-string<Gate>|null
     */
    protected static string|null $gate = null;

    /**
     * The policy to check against.
     *
     * @var class-string<Policy>|null
     */
    protected static string|null $policy = null;

    /**
     * The action to check for.
     *
     * @var string|null
     */
    protected static string|null $action = null;

    /**
     * @inheritDoc
     */
    protected static function checkAuthorized(ServerRequest $request, User $user): bool
    {
        return self::getAuth()->getGate(static::$gate, static::$userEntity, static::$adapterName)
                   ->isAuthorized(
                       static::getAction($request),
                       static::$policy
                   );
    }

    /**
     * The gate.
     *
     * @return string|null
     */
    protected static function getGate(): string|null
    {
        return static::$gate;
    }

    /**
     * Get the action.
     *
     * @param ServerRequest $request The request
     *
     * @return string
     */
    protected static function getAction(ServerRequest $request): string
    {
        /** @var Container $container */
        $route    = $container->getSingleton(Route::class);
        $dispatch = $route->getDispatch();
        $member   = match (true) {
            $dispatch instanceof MethodDispatch   => $dispatch->getMethod(),
            $dispatch instanceof PropertyDispatch => $dispatch->getProperty(),
            $dispatch instanceof ConstantDispatch => $dispatch->getConstant(),
            default                               => null,
        };

        return static::$action
            ?? $member
            ?? $request->getMethod()->value;
    }

    /**
     * The policy.
     *
     * @return string|null
     */
    protected static function getPolicy(): string|null
    {
        return static::$policy;
    }
}
