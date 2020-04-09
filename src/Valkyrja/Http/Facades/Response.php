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

use Valkyrja\Http\Cookie;
use Valkyrja\Http\Enums\FacadeStaticMethod;
use Valkyrja\Http\Response as Contract;
use Valkyrja\Http\Stream;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Response.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract createResponse(string $content = null, int $status = null, array $headers = null)
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
 * @method static int getStatusCode()
 * @method static Contract withStatus(int $code, string $reasonPhrase = null)
 * @method static string getReasonPhrase()
 * @method static Contract withCookie(Cookie $cookie)
 * @method static Contract withoutCookie(Cookie $cookie)
 */
class Response extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }

    /**
     * Get an array of static methods.
     *
     * @return array
     */
    protected static function getStaticMethods(): array
    {
        return [
            FacadeStaticMethod::CREATE_RESPONSE => FacadeStaticMethod::CREATE_RESPONSE,
        ];
    }
}
