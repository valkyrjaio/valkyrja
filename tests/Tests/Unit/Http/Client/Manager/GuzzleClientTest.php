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

namespace Valkyrja\Tests\Unit\Http\Client\Manager;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Client\Manager\GuzzleClient;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Factory\PsrHeaderFactory;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Factory\ResponseFactory;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the GuzzleClient service.
 */
final class GuzzleClientTest extends TestCase
{
    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testSendRequest(): void
    {
        $contents   = 'test';
        $headers    = new HeaderCollection(new Header('header', 'value'));
        $parsedBody = ['param' => 'value'];
        $stringUri  = 'https://example.com/';

        $guzzle       = $this->createMock(Client::class);
        $psr7Response = $this->createMock(ResponseInterface::class);
        $psr7Body     = $this->createMock(StreamInterface::class);

        $responseFactory = new ResponseFactory();

        $body = new Stream();
        $body->write($contents);
        $body->rewind();

        $uri = UriFactory::fromString($stringUri);

        $client  = new GuzzleClient(
            client: $guzzle,
            responseFactory: $responseFactory
        );
        $request = new ServerRequest(
            uri: $uri,
            body: $body,
            headers: $headers,
            cookies: CookieParamCollection::fromArray(['cookie1' => 'value1']),
            parsedBody: ParsedBodyParamCollection::fromArray($parsedBody),
        );

        $psr7Response->expects($this->once())->method('getHeaders')->willReturn(PsrHeaderFactory::toPsr($headers));
        $psr7Response->expects($this->once())->method('getStatusCode')->willReturn(200);
        $psr7Response->expects($this->once())->method('getBody')->willReturn($psr7Body);
        $psr7Body->expects($this->once())->method('getContents')->willReturn($contents);
        $guzzle->expects($this->once())
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
        self::assertSame('value', $response->getHeaders()->getHeaderLine('header'));
        self::assertSame(StatusCode::OK, $response->getStatusCode());
    }
}
