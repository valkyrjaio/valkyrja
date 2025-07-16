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

namespace Valkyrja\Tests\Unit\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Client\GuzzleClient;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\ResponseFactory;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the GuzzleClient service.
 *
 * @author Melech Mizrachi
 */
class GuzzleClientTest extends TestCase
{
    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testSendRequest(): void
    {
        $contents   = 'test';
        $headers    = ['header' => ['value']];
        $parsedBody = ['param' => 'value'];
        $stringUri  = 'https://example.com/';

        $guzzle       = $this->createMock(Client::class);
        $psr7Response = $this->createMock(ResponseInterface::class);
        $psr7Body     = $this->createMock(StreamInterface::class);

        $responseFactory = new ResponseFactory();

        $body = new Stream();
        $body->write($contents);
        $body->rewind();

        $uri = Uri::fromString($stringUri);

        $client  = new GuzzleClient(
            client: $guzzle,
            responseFactory: $responseFactory
        );
        $request = new ServerRequest(
            uri: $uri,
            body: $body,
            headers: $headers,
            cookies: ['cookie1' => 'value1'],
            parsedBody: $parsedBody,
        );
        // $options = [
        //     'headers'     => $request->getHeaders(),
        //     'body'        => 'test',
        //     'cookies'     => $this->any(),
        //     'form_params' => $parsedBody,
        // ];

        $psr7Response->expects(self::once())->method('getHeaders')->willReturn($headers);
        $psr7Response->expects(self::once())->method('getStatusCode')->willReturn(200);
        $psr7Response->expects(self::once())->method('getBody')->willReturn($psr7Body);
        $psr7Body->expects(self::once())->method('getContents')->willReturn($contents);
        $guzzle->expects(self::once())
               ->method('request')
               ->with(
                   'GET',
                   $stringUri,
                   self::logicalAnd(
                       self::arrayHasKey('headers'),
                       self::arrayHasKey('body'),
                       self::arrayHasKey('cookies'),
                       self::arrayHasKey('form_params'),
                   )
               )
               ->willReturn($psr7Response);

        $response = $client->sendRequest($request);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame($contents, $response->getBody()->getContents());
        self::assertSame($headers, $response->getHeaders());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
    }
}
