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

namespace Valkyrja\Tests\Unit\Http\Message\Request;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_filter;

use const ARRAY_FILTER_USE_KEY;

final class ServerRequestTest extends TestCase
{
    public function testServerParams(): void
    {
        $server = [
            'test'  => 'value',
            'test2' => 'foo',
            'bar'   => [],
            'int'   => 1,
            'float' => 1.0,
        ];
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            server: ServerParamCollection::fromArray($server)
        );
        $request3 = $request2->withServerParams(
            ServerParamCollection::fromArray([
                'test4' => 'test',
                'test5' => 'value',
            ])
        );
        $request4 = $request2->withServerParams(
            $request2->getServerParams()->withAddedParams(['test6' => 'null'])
        );
        $request5 = $request2->withServerParams(
            $request2->getServerParams()->withAddedParams(['test6' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getServerParams()->getParams());
        self::assertNotEmpty($request2->getServerParams()->getParams());
        self::assertNotEmpty($request3->getServerParams()->getParams());
        self::assertNotEmpty($request4->getServerParams()->getParams());
        self::assertNotEmpty($request5->getServerParams()->getParams());

        self::assertTrue($request2->getServerParams()->hasParam('test'));
        self::assertTrue($request2->getServerParams()->hasParam('test2'));
        self::assertTrue($request2->getServerParams()->hasParam('bar'));
        self::assertTrue($request2->getServerParams()->hasParam('int'));
        self::assertTrue($request2->getServerParams()->hasParam('float'));
        self::assertFalse($request2->getServerParams()->hasParam('null'));
        self::assertFalse($request2->getServerParams()->hasParam('nonexistent'));

        self::assertFalse($request3->getServerParams()->hasParam('test'));
        self::assertFalse($request3->getServerParams()->hasParam('test2'));
        self::assertTrue($request3->getServerParams()->hasParam('test4'));
        self::assertTrue($request3->getServerParams()->hasParam('test5'));

        self::assertTrue($request4->getServerParams()->hasParam('test'));
        self::assertTrue($request4->getServerParams()->hasParam('test2'));
        self::assertFalse($request4->getServerParams()->hasParam('test4'));
        self::assertFalse($request4->getServerParams()->hasParam('test5'));
        self::assertTrue($request4->getServerParams()->hasParam('test6'));

        self::assertTrue($request5->getServerParams()->hasParam('test'));
        self::assertTrue($request5->getServerParams()->hasParam('test2'));
        self::assertFalse($request5->getServerParams()->hasParam('test4'));
        self::assertFalse($request5->getServerParams()->hasParam('test5'));
        self::assertTrue($request5->getServerParams()->hasParam('test6'));

        self::assertSame('value', $request2->getServerParams()->getParam('test'));
        self::assertSame('foo', $request2->getServerParams()->getParam('test2'));
        self::assertInstanceOf(ServerParamCollection::class, $request2->getServerParams()->getParam('bar'));
        self::assertSame(1, $request2->getServerParams()->getParam('int'));
        self::assertSame(1.0, $request2->getServerParams()->getParam('float'));
        self::assertNull($request2->getServerParams()->getParam('null'));

        self::assertSame('test', $request3->getServerParams()->getParam('test4'));
        self::assertSame('value', $request3->getServerParams()->getParam('test5'));

        self::assertSame('value', $request4->getServerParams()->getParam('test'));
        self::assertSame('foo', $request4->getServerParams()->getParam('test2'));
        self::assertSame('null', $request4->getServerParams()->getParam('test6'));

        self::assertSame('value', $request5->getServerParams()->getParam('test'));
        self::assertSame('foo', $request5->getServerParams()->getParam('test2'));
        self::assertSame('value5', $request5->getServerParams()->getParam('test6'));
    }

    public function testCookies(): void
    {
        $cookies = [
            'cookie'  => 'value',
            'cookie2' => 'foo',
            'cookie3' => 'null',
        ];
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            cookies: CookieParamCollection::fromArray($cookies)
        );
        $request3 = $request2->withCookieParams(
            CookieParamCollection::fromArray([
                'cookie4' => 'test',
                'cookie5' => 'value',
            ])
        );
        $request4 = $request2->withCookieParams(
            $request2->getCookieParams()->withAddedParams(['cookie6' => 'null'])
        );
        $request5 = $request2->withCookieParams(
            $request2->getCookieParams()->withAddedParams(['cookie6' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getCookieParams()->getParams());
        self::assertNotEmpty($request2->getCookieParams()->getParams());
        self::assertNotEmpty($request3->getCookieParams()->getParams());
        self::assertNotEmpty($request4->getCookieParams()->getParams());
        self::assertNotEmpty($request5->getCookieParams()->getParams());

        self::assertTrue($request2->getCookieParams()->hasParam('cookie'));
        self::assertTrue($request2->getCookieParams()->hasParam('cookie2'));
        self::assertTrue($request2->getCookieParams()->hasParam('cookie3'));
        self::assertFalse($request2->getCookieParams()->hasParam('cookie4'));
        self::assertFalse($request2->getCookieParams()->hasParam('cookie5'));
        self::assertFalse($request2->getCookieParams()->hasParam('cookie6'));

        self::assertFalse($request3->getCookieParams()->hasParam('cookie'));
        self::assertFalse($request3->getCookieParams()->hasParam('cookie2'));
        self::assertFalse($request3->getCookieParams()->hasParam('cookie3'));
        self::assertTrue($request3->getCookieParams()->hasParam('cookie4'));
        self::assertTrue($request3->getCookieParams()->hasParam('cookie5'));

        self::assertTrue($request4->getCookieParams()->hasParam('cookie'));
        self::assertTrue($request4->getCookieParams()->hasParam('cookie2'));
        self::assertTrue($request4->getCookieParams()->hasParam('cookie3'));
        self::assertFalse($request4->getCookieParams()->hasParam('cookie4'));
        self::assertFalse($request4->getCookieParams()->hasParam('cookie5'));
        self::assertTrue($request4->getCookieParams()->hasParam('cookie6'));

        self::assertTrue($request5->getCookieParams()->hasParam('cookie'));
        self::assertTrue($request5->getCookieParams()->hasParam('cookie2'));
        self::assertTrue($request5->getCookieParams()->hasParam('cookie3'));
        self::assertFalse($request5->getCookieParams()->hasParam('cookie4'));
        self::assertFalse($request5->getCookieParams()->hasParam('cookie5'));
        self::assertTrue($request5->getCookieParams()->hasParam('cookie6'));

        self::assertSame('value', $request2->getCookieParams()->getParam('cookie'));
        self::assertSame('foo', $request2->getCookieParams()->getParam('cookie2'));
        self::assertSame('null', $request2->getCookieParams()->getParam('cookie3'));
        self::assertNull($request2->getCookieParams()->getParam('nonexistent'));
        self::assertNull($request2->getCookieParams()->getParam('nonexistentWithDefault'));

        self::assertSame('test', $request3->getCookieParams()->getParam('cookie4'));
        self::assertSame('value', $request3->getCookieParams()->getParam('cookie5'));

        self::assertSame('value', $request4->getCookieParams()->getParam('cookie'));
        self::assertSame('foo', $request4->getCookieParams()->getParam('cookie2'));
        self::assertSame('null', $request4->getCookieParams()->getParam('cookie3'));
        self::assertSame('null', $request4->getCookieParams()->getParam('cookie6'));

        self::assertSame('value', $request5->getCookieParams()->getParam('cookie'));
        self::assertSame('foo', $request5->getCookieParams()->getParam('cookie2'));
        self::assertSame('null', $request5->getCookieParams()->getParam('cookie3'));
        self::assertSame('value5', $request5->getCookieParams()->getParam('cookie6'));
    }

    public function testQuery(): void
    {
        $queryParams = [
            'test'  => 'value',
            'test2' => 'foo',
            'bar'   => [],
            'int'   => '1',
            'float' => '1.0',
            2       => 'number',
        ];
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            query: QueryParamCollection::fromArray($queryParams)
        );
        $request3 = $request2->withQueryParams(
            QueryParamCollection::fromArray([
                'param'  => 'test',
                'param2' => 'value',
            ])
        );
        $request4 = $request2->withQueryParams(
            $request2->getQueryParams()->withAddedParams(['param3' => 'null'])
        );
        $request5 = $request2->withQueryParams(
            $request2->getQueryParams()->withAddedParams(['param3' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getQueryParams()->getParams());
        self::assertNotEmpty($request2->getQueryParams()->getParams());
        self::assertNotEmpty($request3->getQueryParams()->getParams());
        self::assertNotEmpty($request4->getQueryParams()->getParams());
        self::assertNotEmpty($request5->getQueryParams()->getParams());

        self::assertSame(
            array_filter(
                $queryParams,
                static fn (string|int $name): bool => $name === 'test2' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getQueryParams()->getOnlyParams('test2', 'null')
        );
        self::assertSameCount(
            array_filter(
                $queryParams,
                static fn (string|int $name): bool => $name !== 'test2' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getQueryParams()->getAllExcept('test2', 'null')
        );

        self::assertTrue($request2->getQueryParams()->hasParam('test'));
        self::assertTrue($request2->getQueryParams()->hasParam('test2'));
        self::assertTrue($request2->getQueryParams()->hasParam('bar'));
        self::assertTrue($request2->getQueryParams()->hasParam('int'));
        self::assertTrue($request2->getQueryParams()->hasParam('float'));
        self::assertFalse($request2->getQueryParams()->hasParam('null'));
        self::assertTrue($request2->getQueryParams()->hasParam(2));
        self::assertFalse($request2->getQueryParams()->hasParam('nonexistent'));
        self::assertFalse($request2->getQueryParams()->hasParam('param'));
        self::assertFalse($request2->getQueryParams()->hasParam('param2'));
        self::assertFalse($request2->getQueryParams()->hasParam('param3'));

        self::assertFalse($request3->getQueryParams()->hasParam('test'));
        self::assertFalse($request3->getQueryParams()->hasParam('test2'));
        self::assertFalse($request3->getQueryParams()->hasParam('bar'));
        self::assertFalse($request3->getQueryParams()->hasParam('int'));
        self::assertFalse($request3->getQueryParams()->hasParam('float'));
        self::assertFalse($request3->getQueryParams()->hasParam('null'));
        self::assertFalse($request3->getQueryParams()->hasParam(2));
        self::assertTrue($request3->getQueryParams()->hasParam('param'));
        self::assertTrue($request3->getQueryParams()->hasParam('param2'));
        self::assertFalse($request3->getQueryParams()->hasParam('param3'));

        self::assertTrue($request4->getQueryParams()->hasParam('test'));
        self::assertTrue($request4->getQueryParams()->hasParam('test2'));
        self::assertTrue($request4->getQueryParams()->hasParam('bar'));
        self::assertTrue($request4->getQueryParams()->hasParam('int'));
        self::assertTrue($request4->getQueryParams()->hasParam('float'));
        self::assertFalse($request4->getQueryParams()->hasParam('null'));
        self::assertTrue($request4->getQueryParams()->hasParam(2));
        self::assertFalse($request4->getQueryParams()->hasParam('param'));
        self::assertFalse($request4->getQueryParams()->hasParam('param2'));
        self::assertTrue($request4->getQueryParams()->hasParam('param3'));

        self::assertTrue($request5->getQueryParams()->hasParam('test'));
        self::assertTrue($request5->getQueryParams()->hasParam('test2'));
        self::assertTrue($request5->getQueryParams()->hasParam('bar'));
        self::assertTrue($request5->getQueryParams()->hasParam('int'));
        self::assertTrue($request5->getQueryParams()->hasParam('float'));
        self::assertFalse($request5->getQueryParams()->hasParam('null'));
        self::assertTrue($request5->getQueryParams()->hasParam(2));
        self::assertFalse($request5->getQueryParams()->hasParam('param'));
        self::assertFalse($request5->getQueryParams()->hasParam('param2'));
        self::assertTrue($request5->getQueryParams()->hasParam('param3'));

        self::assertSame('value', $request2->getQueryParams()->getParam('test'));
        self::assertSame('foo', $request2->getQueryParams()->getParam('test2'));
        self::assertInstanceOf(QueryParamCollection::class, $request2->getQueryParams()->getParam('bar'));
        self::assertSame('1', $request2->getQueryParams()->getParam('int'));
        self::assertSame('1.0', $request2->getQueryParams()->getParam('float'));
        self::assertNull($request2->getQueryParams()->getParam('null'));
        self::assertSame('number', $request2->getQueryParams()->getParam(2));
        self::assertNull($request2->getQueryParams()->getParam('nonexistentWithDefault'));

        self::assertSame('test', $request3->getQueryParams()->getParam('param'));
        self::assertSame('value', $request3->getQueryParams()->getParam('param2'));

        self::assertSame('value', $request4->getQueryParams()->getParam('test'));
        self::assertSame('foo', $request4->getQueryParams()->getParam('test2'));
        self::assertInstanceOf(QueryParamCollection::class, $request4->getQueryParams()->getParam('bar'));
        self::assertSame('1', $request4->getQueryParams()->getParam('int'));
        self::assertSame('1.0', $request4->getQueryParams()->getParam('float'));
        self::assertNull($request4->getQueryParams()->getParam('null'));
        self::assertSame('number', $request4->getQueryParams()->getParam(2));
        self::assertSame('null', $request4->getQueryParams()->getParam('param3'));

        self::assertSame('value', $request5->getQueryParams()->getParam('test'));
        self::assertSame('foo', $request5->getQueryParams()->getParam('test2'));
        self::assertInstanceOf(QueryParamCollection::class, $request5->getQueryParams()->getParam('bar'));
        self::assertSame('1', $request5->getQueryParams()->getParam('int'));
        self::assertSame('1.0', $request5->getQueryParams()->getParam('float'));
        self::assertNull($request5->getQueryParams()->getParam('null'));
        self::assertSame('number', $request5->getQueryParams()->getParam(2));
        self::assertSame('value5', $request5->getQueryParams()->getParam('param3'));
    }

    public function testUploadedFiles(): void
    {
        $request  = new ServerRequest();
        $request2 = $request->withUploadedFiles(
            new UploadedFileCollection([
                new UploadedFile(file: 'test'),
                new UploadedFile(file: 'test'),
            ])
        );
        $request3 = $request->withUploadedFiles(
            $request->getUploadedFiles()->withAdded(
                [new UploadedFile(file: 'test')]
            )
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request, $request3);

        self::assertEmpty($request->getUploadedFiles()->getAll());
        self::assertNotEmpty($request2->getUploadedFiles()->getAll());
        self::assertNotEmpty($request3->getUploadedFiles()->getAll());

        self::assertCount(2, $request2->getUploadedFiles()->getAll());
        self::assertCount(1, $request3->getUploadedFiles()->getAll());
    }

    public function testParsedBody(): void
    {
        $bodyParams = [
            'test'  => 'value',
            'test2' => 'foo',
            'bar'   => [],
            'int'   => '1',
            'float' => '1.0',
            2       => 'number',
        ];
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            parsedBody: ParsedBodyParamCollection::fromArray($bodyParams)
        );
        $request3 = $request2->withParsedBody(
            ParsedBodyParamCollection::fromArray([
                'param'  => 'test',
                'param2' => 'value',
            ])
        );
        $request4 = $request2->withParsedBody(
            $request2->getParsedBody()->withAddedParams(['param3' => 'null'])
        );
        $request5 = $request2->withParsedBody(
            $request2->getParsedBody()->withAddedParams(['param3' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getParsedBody()->getParams());
        self::assertNotEmpty($request2->getParsedBody()->getParams());
        self::assertNotEmpty($request3->getParsedBody()->getParams());
        self::assertNotEmpty($request4->getParsedBody()->getParams());
        self::assertNotEmpty($request5->getParsedBody()->getParams());

        self::assertSame(
            array_filter(
                $bodyParams,
                static fn (string|int $name): bool => $name === 'test2' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getParsedBody()->getOnlyParams('test2', 'null')
        );
        self::assertSameCount(
            array_filter(
                $bodyParams,
                static fn (string|int $name): bool => $name !== 'test2' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getParsedBody()->getAllExcept('test2', 'null')
        );

        self::assertTrue($request2->getParsedBody()->hasParam('test'));
        self::assertTrue($request2->getParsedBody()->hasParam('test2'));
        self::assertTrue($request2->getParsedBody()->hasParam('bar'));
        self::assertTrue($request2->getParsedBody()->hasParam('int'));
        self::assertTrue($request2->getParsedBody()->hasParam('float'));
        self::assertFalse($request2->getParsedBody()->hasParam('null'));
        self::assertTrue($request2->getParsedBody()->hasParam(2));
        self::assertFalse($request2->getParsedBody()->hasParam('nonexistent'));
        self::assertFalse($request2->getParsedBody()->hasParam('param'));
        self::assertFalse($request2->getParsedBody()->hasParam('param2'));
        self::assertFalse($request2->getParsedBody()->hasParam('param3'));

        self::assertFalse($request3->getParsedBody()->hasParam('test'));
        self::assertFalse($request3->getParsedBody()->hasParam('test2'));
        self::assertFalse($request3->getParsedBody()->hasParam('bar'));
        self::assertFalse($request3->getParsedBody()->hasParam('int'));
        self::assertFalse($request3->getParsedBody()->hasParam('float'));
        self::assertFalse($request3->getParsedBody()->hasParam('null'));
        self::assertFalse($request3->getParsedBody()->hasParam(2));
        self::assertTrue($request3->getParsedBody()->hasParam('param'));
        self::assertTrue($request3->getParsedBody()->hasParam('param2'));
        self::assertFalse($request3->getParsedBody()->hasParam('param3'));

        self::assertTrue($request4->getParsedBody()->hasParam('test'));
        self::assertTrue($request4->getParsedBody()->hasParam('test2'));
        self::assertTrue($request4->getParsedBody()->hasParam('bar'));
        self::assertTrue($request4->getParsedBody()->hasParam('int'));
        self::assertTrue($request4->getParsedBody()->hasParam('float'));
        self::assertFalse($request4->getParsedBody()->hasParam('null'));
        self::assertTrue($request4->getParsedBody()->hasParam(2));
        self::assertFalse($request4->getParsedBody()->hasParam('param'));
        self::assertFalse($request4->getParsedBody()->hasParam('param2'));
        self::assertTrue($request4->getParsedBody()->hasParam('param3'));

        self::assertTrue($request5->getParsedBody()->hasParam('test'));
        self::assertTrue($request5->getParsedBody()->hasParam('test2'));
        self::assertTrue($request5->getParsedBody()->hasParam('bar'));
        self::assertTrue($request5->getParsedBody()->hasParam('int'));
        self::assertTrue($request5->getParsedBody()->hasParam('float'));
        self::assertFalse($request5->getParsedBody()->hasParam('null'));
        self::assertTrue($request5->getParsedBody()->hasParam(2));
        self::assertFalse($request5->getParsedBody()->hasParam('param'));
        self::assertFalse($request5->getParsedBody()->hasParam('param2'));
        self::assertTrue($request5->getParsedBody()->hasParam('param3'));

        self::assertSame('value', $request2->getParsedBody()->getParam('test'));
        self::assertSame('foo', $request2->getParsedBody()->getParam('test2'));
        self::assertInstanceOf(ParsedBodyParamCollection::class, $request2->getParsedBody()->getParam('bar'));
        self::assertSame('1', $request2->getParsedBody()->getParam('int'));
        self::assertSame('1.0', $request2->getParsedBody()->getParam('float'));
        self::assertNull($request2->getParsedBody()->getParam('null'));
        self::assertSame('number', $request2->getParsedBody()->getParam(2));
        self::assertNull($request2->getParsedBody()->getParam('nonexistentWithDefault'));

        self::assertSame('test', $request3->getParsedBody()->getParam('param'));
        self::assertSame('value', $request3->getParsedBody()->getParam('param2'));

        self::assertSame('value', $request4->getParsedBody()->getParam('test'));
        self::assertSame('foo', $request4->getParsedBody()->getParam('test2'));
        self::assertInstanceOf(ParsedBodyParamCollection::class, $request4->getParsedBody()->getParam('bar'));
        self::assertSame('1', $request4->getParsedBody()->getParam('int'));
        self::assertSame('1.0', $request4->getParsedBody()->getParam('float'));
        self::assertNull($request4->getParsedBody()->getParam('null'));
        self::assertSame('number', $request4->getParsedBody()->getParam(2));
        self::assertSame('null', $request4->getParsedBody()->getParam('param3'));

        self::assertSame('value', $request5->getParsedBody()->getParam('test'));
        self::assertSame('foo', $request5->getParsedBody()->getParam('test2'));
        self::assertInstanceOf(ParsedBodyParamCollection::class, $request5->getParsedBody()->getParam('bar'));
        self::assertSame('1', $request5->getParsedBody()->getParam('int'));
        self::assertSame('1.0', $request5->getParsedBody()->getParam('float'));
        self::assertNull($request5->getParsedBody()->getParam('null'));
        self::assertSame('number', $request5->getParsedBody()->getParam(2));
        self::assertSame('value5', $request5->getParsedBody()->getParam('param3'));
    }

    public function testAttributes(): void
    {
        $request  = new ServerRequest();
        $request2 = $request->withAttribute('test', 2);
        $request3 = $request2->withAttribute('test2', null);
        $request4 = $request3->withAttribute('test4', 'test');
        $request5 = $request3->withoutAttribute('test4');

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getAttributes());
        self::assertNotEmpty($request2->getAttributes());
        self::assertNotEmpty($request3->getAttributes());
        self::assertNotEmpty($request4->getAttributes());
        self::assertNotEmpty($request5->getAttributes());

        self::assertSame(
            ['test2' => null],
            $request4->onlyAttributes('test2')
        );
        self::assertSame(
            ['test' => 2, 'test4' => 'test'],
            $request4->exceptAttributes('test2')
        );

        self::assertSame(2, $request2->getAttribute('test'));
        self::assertNull($request2->getAttribute('nonexistent'));
        self::assertSame('default', $request2->getAttribute('nonexistentWithDefault', 'default'));

        self::assertSame(2, $request3->getAttribute('test'));
        self::assertNull($request3->getAttribute('test2'));

        self::assertSame(2, $request4->getAttribute('test'));
        self::assertNull($request4->getAttribute('test2'));
        self::assertSame('test', $request4->getAttribute('test4'));

        self::assertSame(2, $request5->getAttribute('test'));
        self::assertNull($request5->getAttribute('test2'));
    }

    public function testIsXmlHttpRequest(): void
    {
        $request  = new ServerRequest();
        $request2 = new ServerRequest(headers: new HeaderCollection(new Header(HeaderName::X_REQUESTED_WITH, 'XMLHttpRequest')));
        $request3 = $request->withHeaders(
            $request->getHeaders()->withHeader(new Header(HeaderName::X_REQUESTED_WITH, 'XMLHttpRequest'))
        );
        $request4 = $request->withHeaders(
            $request->getHeaders()->withoutHeader(HeaderName::X_REQUESTED_WITH)
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request, $request3);
        self::assertNotSame($request, $request4);

        self::assertFalse($request->isXmlHttpRequest());
        self::assertTrue($request2->isXmlHttpRequest());
        self::assertTrue($request3->isXmlHttpRequest());
        self::assertFalse($request4->isXmlHttpRequest());
    }
}
