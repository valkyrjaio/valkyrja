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

namespace Valkyrja\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;

class HttpRedirectException extends HttpException
{
    /**
     * The uri to redirect to for this exception.
     *
     * @var UriContract
     */
    protected UriContract $uri;

    /**
     * HttpRedirectException constructor.
     *
     * @see http://php.net/manual/en/exception.construct.php
     *
     * @param StatusCode|null              $statusCode [optional] The status code to use
     * @param UriContract|null             $uri        [optional] The uri to redirect to
     * @param array<string, string[]>|null $headers    [optional] The headers to send
     * @param ResponseContract|null        $response   [optional] The Response
     */
    public function __construct(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null,
        ResponseContract|null $response = null
    ) {
        $statusCode ??= StatusCode::FOUND;
        $headers ??= [];
        $uri ??= new HttpUri(path: '/');
        // Set a new redirect response if one wasn't passed in
        $response ??= RedirectResponse::createFromUri($uri, $statusCode, $headers);
        // Set the uri
        $this->uri = $uri;

        parent::__construct($statusCode, 'Redirect', $headers, $response);
    }

    /**
     * Get the uri to redirect to for this exception.
     *
     * @return UriContract
     */
    public function getUri(): UriContract
    {
        return $this->uri;
    }
}
