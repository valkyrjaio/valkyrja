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

namespace Valkyrja\Tests\Unit\Http\Message\Request\Psr;

use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\Psr\UploadedFile as PsrUploadedFile;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Http\Message\Request\Psr\ServerRequest as PsrServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ServerRequestTest extends TestCase
{
    public function testServerParam(): void
    {
        $server     = ['test' => 'value'];
        $request    = new ServerRequest(server: ServerParamCollection::fromArray($server));
        $psrRequest = new PsrServerRequest($request);

        self::assertSame($request->getServerParams()->getParams(), $psrRequest->getServerParams());
        self::assertSame($server, $request->getServerParams()->getParams());
        self::assertSame($server, $psrRequest->getServerParams());
    }

    public function testCookies(): void
    {
        $cookies    = ['test' => 'value'];
        $request    = new ServerRequest(cookies: CookieParamCollection::fromArray($cookies));
        $psrRequest = new PsrServerRequest($request);

        $cookies2 = ['test2' => 'value2'];

        $psrRequest2 = $psrRequest->withCookieParams($cookies2);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($request->getCookieParams()->getParams(), $psrRequest->getCookieParams());
        self::assertSame($cookies, $psrRequest->getCookieParams());
        self::assertSame($cookies2, $psrRequest2->getCookieParams());
    }

    public function testQuery(): void
    {
        $query      = ['test' => 'value'];
        $request    = new ServerRequest(query: QueryParamCollection::fromArray($query));
        $psrRequest = new PsrServerRequest($request);

        $query2 = ['test2' => 'value2'];

        $psrRequest2 = $psrRequest->withQueryParams($query2);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($request->getQueryParams()->getParams(), $psrRequest->getQueryParams());
        self::assertSame($query, $psrRequest->getQueryParams());
        self::assertSame($query2, $psrRequest2->getQueryParams());
    }

    public function testUploadedFiles(): void
    {
        $uploadedFiles = [
            new UploadedFile(file: 'test'),
            new UploadedFile(file: 'test'),
        ];
        $request       = new ServerRequest()->withUploadedFiles(UploadedFileCollection::fromArray($uploadedFiles));
        $psrRequest    = new PsrServerRequest($request);

        $uploadedFiles2 = [
            new PsrUploadedFile(new UploadedFile(file: 'test')),
            new PsrUploadedFile(new UploadedFile(file: 'test')),
        ];

        $psrRequest2 = $psrRequest->withUploadedFiles($uploadedFiles2);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertCount(2, $psrRequest->getUploadedFiles());
        self::assertCount(2, $psrRequest2->getUploadedFiles());
    }

    public function testParsedBody(): void
    {
        $parsedBody = ['test' => 'value'];
        $request    = new ServerRequest(parsedBody: ParsedBodyParamCollection::fromArray($parsedBody));
        $psrRequest = new PsrServerRequest($request);

        $parsedBody2 = ['test2' => 'value2'];

        $psrRequest2 = $psrRequest->withParsedBody($parsedBody2);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($request->getParsedBody()->getParams(), $psrRequest->getParsedBody());
        self::assertSame($parsedBody, $psrRequest->getParsedBody());
        self::assertSame($parsedBody2, $psrRequest2->getParsedBody());
    }

    public function testAttributes(): void
    {
        $attributes = ['test' => 'value'];
        $request    = new ServerRequest()->withAttribute('test', 'value');
        $psrRequest = new PsrServerRequest($request);

        $attributes2 = ['test2' => 'value2'];

        $psrRequest2 = $psrRequest->withAttribute('test2', 'value2');
        $psrRequest3 = $psrRequest2->withoutAttribute('test');

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertNotSame($psrRequest2, $psrRequest3);
        self::assertSame($request->getAttributes(), $psrRequest->getAttributes());
        self::assertSame($attributes, $psrRequest->getAttributes());
        self::assertSame($attributes + $attributes2, $psrRequest2->getAttributes());
        self::assertSame($attributes2, $psrRequest3->getAttributes());

        self::assertSame('value', $psrRequest->getAttribute('test'));

        self::assertSame('value', $psrRequest2->getAttribute('test'));
        self::assertSame('value2', $psrRequest2->getAttribute('test2'));

        self::assertNull($psrRequest3->getAttribute('test'));
        self::assertSame('value2', $psrRequest3->getAttribute('test2'));
    }
}
