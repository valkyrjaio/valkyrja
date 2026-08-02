<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Uri\Factory;

use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Uri;

use function explode;
use function str_contains;

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
        $password = '';

        if ($userInfo !== '' && str_contains($userInfo, ':')) {
            [$user, $password] = explode(':', $userInfo);
        } else {
            $user = $userInfo;
        }

        $uri = new Uri();

        $uri = $uri
            ->withScheme(Scheme::from($psrUri->getScheme()))
            ->withUserInfo($user, $password)
            ->withHost($psrUri->getHost())
            ->withPath($psrUri->getPath())
            ->withQuery($psrUri->getQuery())
            ->withFragment($psrUri->getFragment());

        $port = $psrUri->getPort();

        if ($port !== null) {
            $uri = $uri->withPort($port);
        }

        return $uri;
    }
}
