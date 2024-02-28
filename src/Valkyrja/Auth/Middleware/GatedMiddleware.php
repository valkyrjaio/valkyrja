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

use Valkyrja\Auth\Gate;
use Valkyrja\Auth\Policy;
use Valkyrja\Auth\User;
use Valkyrja\Http\Request;

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
    protected static function checkAuthorized(Request $request, User $user): bool
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
     * @param Request $request The request
     *
     * @return string
     */
    protected static function getAction(Request $request): string
    {
        return static::$action
            ?? self::getRoute()?->getMethod()
            ?? self::getRoute()?->getProperty()
            ?? $request->getMethod();
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
