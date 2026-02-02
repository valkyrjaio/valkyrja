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

namespace Valkyrja\Http\Routing\Constant;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Value\Component\Component;
use Valkyrja\Http\Message\Header\Value\Cookie;
use Valkyrja\Http\Message\Header\Value\Value;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Http\Message\Response\HtmlResponse;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Message\Response\XmlResponse;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Uri;

final class AllowedClasses
{
    /** @var class-string[] */
    public const array CACHE_RESPONSE_MIDDLEWARE = [
        Response::class,
        EmptyResponse::class,
        HtmlResponse::class,
        JsonResponse::class,
        RedirectResponse::class,
        TextResponse::class,
        XmlResponse::class,
        ProtocolVersion::class,
        StatusCode::class,
        Stream::class,
        Uri::class,
        Header::class,
        Value::class,
        Cookie::class,
        Component::class,
    ];
}
