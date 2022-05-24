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

namespace Valkyrja\Http\Facades;

use Valkyrja\Http\Request as Contract;
use Valkyrja\Http\Stream;
use Valkyrja\Http\UploadedFile;
use Valkyrja\Http\Uri;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Request.
 *
 * @author Melech Mizrachi
 *
 * @method static string getProtocolVersion()
 * @method static Contract withProtocolVersion(string $version)
 * @method static array getHeaders()
 * @method static bool hasHeader(string $name)
 * @method static array getHeader(string $name)
 * @method static string getHeaderLine(string $name)
 * @method static Contract withHeader(string $name, string ...$value)
 * @method static Contract withAddedHeader(string $name, string ...$value)
 * @method static Contract withoutHeader(string $name)
 * @method static Stream getBody()
 * @method static Contract withBody(Stream $body)
 * @method static string getRequestTarget()
 * @method static Contract withRequestTarget(string $requestTarget)
 * @method static string getMethod()
 * @method static Contract withMethod(string $method)
 * @method static Uri getUri()
 * @method static Contract withUri(Uri $uri, bool $preserveHost = false)
 * @method static array getServerParams()
 * @method static mixed getServerParam(string $name, $default = null)
 * @method static bool hasServerParam(string $name)
 * @method static array getCookieParams()
 * @method static Contract withCookieParams(array $cookies)
 * @method static string|null getCookieParam(string $name)
 * @method static bool hasCookieParam(string $name)
 * @method static array getQueryParams()
 * @method static array onlyQueryParams(array $names)
 * @method static array exceptQueryParams(array $names)
 * @method static Contract withQueryParams(array $query)
 * @method static mixed getQueryParam(string $name, $default = null)
 * @method static bool hasQueryParam(string $name)
 * @method static UploadedFile[] getUploadedFiles()
 * @method static Contract withUploadedFiles(UploadedFile ...$uploadedFiles)
 * @method static array getParsedBody()
 * @method static array onlyParsedBody(array $names)
 * @method static array exceptParsedBody(array $names)
 * @method static Contract withParsedBody(array $data)
 * @method static mixed getParsedBodyParam(string $name, $default = null)
 * @method static bool hasParsedBodyParam(string $name)
 * @method static array getParsedJson()
 * @method static array onlyParsedJson(array $names)
 * @method static array exceptParsedJson(array $names)
 * @method static mixed getParsedJsonParam(string $name, $default = null)
 * @method static bool hasParsedJsonParam(string $name)
 * @method static array getAttributes()
 * @method static array onlyAttributes(array $names)
 * @method static array exceptAttributes(array $names)
 * @method static mixed getAttribute(string $name, $default = null)
 * @method static Contract withAttribute(string $name, $value)
 * @method static Contract withoutAttribute(string $name)
 * @method static bool isXmlHttpRequest()
 */
class Request extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
