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

use Valkyrja\Auth\User;

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
     * @var string|null
     */
    protected static ?string $gate = null;

    /**
     * The policy to check against.
     *
     * @var string|null
     */
    protected static ?string $policy = null;

    /**
     * The action to check for.
     *
     * @var string
     */
    protected static string $action;

    /**
     * @inheritDoc
     */
    protected static function checkAuthorized(User $user): bool
    {
        return self::$auth->getGate(static::$gate, static::$userEntity, static::$adapterName)
            ->isAuthorized(static:: $action, static::$policy);
    }
}
