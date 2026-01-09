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

namespace Valkyrja\Tests\Unit\Http\Message\Factory;

use JsonException;
use Valkyrja\Http\Message\Constant\ContentType;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\ResponseFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class ResponseFactoryTest extends TestCase
{
    public function testCreateResponse(): void
    {
        $responseFactory = new ResponseFactory();

        $default  = $responseFactory->createResponse();
        $response = $responseFactory->createResponse(
            content: $content       = 'test',
            statusCode: $statusCode = StatusCode::CREATED,
            headers: [
                'test' => 'foo',
            ]
        );

        self::assertSame('', $default->getBody()->getContents());
        self::assertSame(StatusCode::OK, $default->getStatusCode());
        self::assertSame([], $default->getHeaders());

        self::assertSame($content, $response->getBody()->getContents());
        self::assertSame($statusCode, $response->getStatusCode());
        self::assertSame(
            [
                'test' => ['foo'],
            ],
            $response->getHeaders()
        );
    }

    public function testCreateTextResponse(): void
    {
        $responseFactory = new ResponseFactory();

        $default  = $responseFactory->createTextResponse();
        $response = $responseFactory->createTextResponse(
            content: $content       = 'test',
            statusCode: $statusCode = StatusCode::CREATED,
            headers: [
                'test' => 'foo',
            ]
        );

        self::assertSame('', $default->getBody()->getContents());
        self::assertSame(StatusCode::OK, $default->getStatusCode());
        self::assertSame(['Content-Type' => ['text/plain; charset=utf-8']], $default->getHeaders());

        self::assertSame($content, $response->getBody()->getContents());
        self::assertSame($statusCode, $response->getStatusCode());
        self::assertSame(
            [
                'test'         => ['foo'],
                'Content-Type' => ['text/plain; charset=utf-8'],
            ],
            $response->getHeaders()
        );
    }

    /**
     * @throws JsonException
     */
    public function testCreateJsonResponse(): void
    {
        $responseFactory = new ResponseFactory();

        $default  = $responseFactory->createJsonResponse();
        $response = $responseFactory->createJsonResponse(
            data: $data             = ['test' => 'bar'],
            statusCode: $statusCode = StatusCode::CREATED,
            headers: [
                'test' => 'foo',
            ]
        );

        self::assertSame('[]', $default->getBody()->getContents());
        self::assertSame(StatusCode::OK, $default->getStatusCode());
        self::assertSame([HeaderName::CONTENT_TYPE => [ContentType::APPLICATION_JSON]], $default->getHeaders());

        self::assertSame(json_encode($data, JSON_THROW_ON_ERROR), $response->getBody()->getContents());
        self::assertSame($statusCode, $response->getStatusCode());
        self::assertSame(
            [
                'test'                   => ['foo'],
                HeaderName::CONTENT_TYPE => [ContentType::APPLICATION_JSON],
            ],
            $response->getHeaders()
        );
    }

    /**
     * @throws JsonException
     */
    public function testCreateJsonpResponse(): void
    {
        $responseFactory = new ResponseFactory();
        $callback        = 'callbackMethod';

        $default  = $responseFactory->createJsonpResponse($callback);
        $response = $responseFactory->createJsonpResponse(
            callback: $callback,
            data: ['test' => 'bar'],
            statusCode: $statusCode = StatusCode::CREATED,
            headers: [
                'test' => 'foo',
            ]
        );

        self::assertSame('/**/callbackMethod([]);', $default->getBody()->getContents());
        self::assertSame(StatusCode::OK, $default->getStatusCode());
        self::assertSame([HeaderName::CONTENT_TYPE => [ContentType::TEXT_JAVASCRIPT]], $default->getHeaders());

        self::assertSame('/**/callbackMethod({"test":"bar"});', $response->getBody()->getContents());
        self::assertSame($statusCode, $response->getStatusCode());
        self::assertSame(
            [
                'test'                   => ['foo'],
                HeaderName::CONTENT_TYPE => [ContentType::TEXT_JAVASCRIPT],
            ],
            $response->getHeaders()
        );
    }

    public function testCreateRedirectResponse(): void
    {
        $responseFactory = new ResponseFactory();

        $default  = $responseFactory->createRedirectResponse();
        $response = $responseFactory->createRedirectResponse(
            uri: $uri               = '/redirect-path',
            statusCode: $statusCode = StatusCode::PERMANENT_REDIRECT,
            headers: [
                'test' => 'foo',
            ]
        );

        self::assertSame(StatusCode::FOUND, $default->getStatusCode());
        self::assertSame([HeaderName::LOCATION => ['/']], $default->getHeaders());

        self::assertSame($statusCode, $response->getStatusCode());
        self::assertSame(
            [
                'test'               => ['foo'],
                HeaderName::LOCATION => [$uri],
            ],
            $response->getHeaders()
        );
    }
}
