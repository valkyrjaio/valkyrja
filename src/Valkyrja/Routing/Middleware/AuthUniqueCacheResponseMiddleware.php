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

use Valkyrja\Auth\Facades\Auth;
use Valkyrja\Http\Request;

use function md5;

/**
 * Class AuthUniqueCacheResponseMiddleware.
 *
 * @author Melech Mizrachi
 */
class AuthUniqueCacheResponseMiddleware extends CacheResponseMiddleware
{
    /**
     * @inheritDoc
     */
    protected static function getHashedPath(Request $request): string
    {
        $userPart = '';

        if (Auth::isAuthenticated()) {
            $userPart = md5(Auth::getUser()->__toString());
        }

        return parent::getHashedPath($request) . $userPart;
    }
}
