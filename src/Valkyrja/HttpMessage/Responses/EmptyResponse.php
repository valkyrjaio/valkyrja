<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Responses;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\HttpMessage\EmptyResponse as EmptyResponseContract;
use Valkyrja\HttpMessage\Exceptions\InvalidStatusCode;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;

/**
 * Class NativeEmptyResponse.
 *
 * @author Melech Mizrachi
 */
class EmptyResponse extends Response implements EmptyResponseContract
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
            EmptyResponseContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(EmptyResponseContract::class, new static());
    }
}
