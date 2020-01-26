<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Response;

use InvalidArgumentException;
use Valkyrja\Application;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\HttpMessage\Enums\Header;
use Valkyrja\HttpMessage\Exceptions\InvalidStatusCode;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\NativeResponse;

/**
 * Class NativeRedirectResponse.
 *
 * @author Melech Mizrachi
 */
class NativeRedirectResponse extends NativeResponse implements RedirectResponse
{
    /**
     * NativeRedirectResponse constructor.
     *
     * @param string $uri     The uri
     * @param int    $status  [optional] The status
     * @param array  $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(string $uri = '/', int $status = null, array $headers = [])
    {
        parent::__construct(
            null,
            $status ?? StatusCode::FOUND,
            $this->injectHeader(Header::LOCATION, $uri, $headers, true)
        );
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RedirectResponse::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(RedirectResponse::class, new static());
    }
}
