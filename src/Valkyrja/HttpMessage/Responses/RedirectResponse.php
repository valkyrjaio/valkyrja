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
use Valkyrja\HttpMessage\Enums\Header;
use Valkyrja\HttpMessage\Exceptions\InvalidStatusCode;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\RedirectResponse as RedirectResponseContract;

/**
 * Class NativeRedirectResponse.
 *
 * @author Melech Mizrachi
 */
class RedirectResponse extends Response implements RedirectResponseContract
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
            RedirectResponseContract::class,
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
        $app->container()->singleton(RedirectResponseContract::class, new static());
    }
}