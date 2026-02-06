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

namespace Valkyrja\Http\Message\Uri\Factory;

use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Uri;

use function explode;

abstract class PsrUriFactory
{
    /**
     * Get a Uri object from a PSR UriInterface object.
     *
     * @param UriInterface $psrUri The PSR uri
     */
    public static function fromPsr(UriInterface $psrUri): UriContract
    {
        $userInfo = $psrUri->getUserInfo();
        $password = null;

        if ($userInfo !== '' && str_contains($userInfo, ':')) {
            [$user, $password] = explode(':', $userInfo);
        } else {
            $user = $userInfo;
        }

        $uri = new Uri();

        return $uri
            ->withScheme(Scheme::from($psrUri->getScheme()))
            ->withUserInfo($user, $password)
            ->withHost($psrUri->getHost())
            ->withPort($psrUri->getPort())
            ->withPath($psrUri->getPath())
            ->withQuery($psrUri->getQuery())
            ->withFragment($psrUri->getFragment());
    }
}
