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
use Valkyrja\HttpMessage\Exceptions\InvalidStatusCode;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\NativeResponse;

/**
 * Class NativeEmptyResponse.
 *
 * @author Melech Mizrachi
 */
class NativeEmptyResponse extends NativeResponse implements EmptyResponse
{
    /**
     * NativeEmptyResponse constructor.
     *
     * @param array $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(array $headers = [])
    {
        parent::__construct(null, StatusCode::NO_CONTENT, $headers);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            EmptyResponse::class,
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
        $app->container()->singleton(EmptyResponse::class, new static());
    }
}
