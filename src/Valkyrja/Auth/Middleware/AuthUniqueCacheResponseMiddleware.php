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

use Valkyrja\Auth\Contract\Auth;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Filesystem\InMemoryFilesystem;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;

use function md5;

/**
 * Class AuthUniqueCacheResponseMiddleware.
 *
 * @author Melech Mizrachi
 */
class AuthUniqueCacheResponseMiddleware extends CacheResponseMiddleware
{
    public function __construct(
        protected Auth $auth,
        Filesystem $filesystem = new InMemoryFilesystem(),
        bool $debug = false
    ) {
        parent::__construct(
            filesystem: $filesystem,
            debug: $debug
        );
    }

    /**
     * @inheritDoc
     */
    protected function getHashedPath(ServerRequest $request): string
    {
        $auth     = $this->auth;
        $userPart = '';

        if ($auth->isAuthenticated()) {
            $userPart = md5($auth->getUser()->__toString());
        }

        return parent::getHashedPath($request) . $userPart;
    }
}
