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
            $request2->getServerParams()->withAdded(['test6' => 'null'])
        );
        $request5 = $request2->withServerParams(
            $request2->getServerParams()->withAdded(['test6' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getServerParams()->getAll());
        self::assertNotEmpty($request2->getServerParams()->getAll());
        self::assertNotEmpty($request3->getServerParams()->getAll());
        self::assertNotEmpty($request4->getServerParams()->getAll());
        self::assertNotEmpty($request5->getServerParams()->getAll());

        self::assertTrue($request2->getServerParams()->has('test'));
        self::assertTrue($request2->getServerParams()->has('test2'));
        self::assertTrue($request2->getServerParams()->has('bar'));
        self::assertTrue($request2->getServerParams()->has('int'));
        self::assertTrue($request2->getServerParams()->has('float'));
        self::assertFalse($request2->getServerParams()->has('null'));
        self::assertFalse($request2->getServerParams()->has('nonexistent'));

        self::assertFalse($request3->getServerParams()->has('test'));
        self::assertFalse($request3->getServerParams()->has('test2'));
        self::assertTrue($request3->getServerParams()->has('test4'));
        self::assertTrue($request3->getServerParams()->has('test5'));

        self::assertTrue($request4->getServerParams()->has('test'));
        self::assertTrue($request4->getServerParams()->has('test2'));
        self::assertFalse($request4->getServerParams()->has('test4'));
        self::assertFalse($request4->getServerParams()->has('test5'));
        self::assertTrue($request4->getServerParams()->has('test6'));

        self::assertTrue($request5->getServerParams()->has('test'));
        self::assertTrue($request5->getServerParams()->has('test2'));
        self::assertFalse($request5->getServerParams()->has('test4'));
        self::assertFalse($request5->getServerParams()->has('test5'));
        self::assertTrue($request5->getServerParams()->has('test6'));

        self::assertSame('value', $request2->getServerParams()->get('test'));
        self::assertSame('foo', $request2->getServerParams()->get('test2'));
        self::assertInstanceOf(ServerParamCollection::class, $request2->getServerParams()->get('bar'));
        self::assertSame(1, $request2->getServerParams()->get('int'));
        self::assertSame(1.0, $request2->getServerParams()->get('float'));
        self::assertNull($request2->getServerParams()->get('null'));

        self::assertSame('test', $request3->getServerParams()->get('test4'));
        self::assertSame('value', $request3->getServerParams()->get('test5'));

        self::assertSame('value', $request4->getServerParams()->get('test'));
        self::assertSame('foo', $request4->getServerParams()->get('test2'));
        self::assertSame('null', $request4->getServerParams()->get('test6'));

        self::assertSame('value', $request5->getServerParams()->get('test'));
        self::assertSame('foo', $request5->getServerParams()->get('test2'));
        self::assertSame('value5', $request5->getServerParams()->get('test6'));
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
            $request2->getCookieParams()->withAdded(['cookie6' => 'null'])
        );
        $request5 = $request2->withCookieParams(
            $request2->getCookieParams()->withAdded(['cookie6' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getCookieParams()->getAll());
        self::assertNotEmpty($request2->getCookieParams()->getAll());
        self::assertNotEmpty($request3->getCookieParams()->getAll());
        self::assertNotEmpty($request4->getCookieParams()->getAll());
        self::assertNotEmpty($request5->getCookieParams()->getAll());

        self::assertTrue($request2->getCookieParams()->has('cookie'));
        self::assertTrue($request2->getCookieParams()->has('cookie2'));
        self::assertTrue($request2->getCookieParams()->has('cookie3'));
        self::assertFalse($request2->getCookieParams()->has('cookie4'));
        self::assertFalse($request2->getCookieParams()->has('cookie5'));
        self::assertFalse($request2->getCookieParams()->has('cookie6'));

        self::assertFalse($request3->getCookieParams()->has('cookie'));
        self::assertFalse($request3->getCookieParams()->has('cookie2'));
        self::assertFalse($request3->getCookieParams()->has('cookie3'));
        self::assertTrue($request3->getCookieParams()->has('cookie4'));
        self::assertTrue($request3->getCookieParams()->has('cookie5'));

        self::assertTrue($request4->getCookieParams()->has('cookie'));
        self::assertTrue($request4->getCookieParams()->has('cookie2'));
        self::assertTrue($request4->getCookieParams()->has('cookie3'));
        self::assertFalse($request4->getCookieParams()->has('cookie4'));
        self::assertFalse($request4->getCookieParams()->has('cookie5'));
        self::assertTrue($request4->getCookieParams()->has('cookie6'));

        self::assertTrue($request5->getCookieParams()->has('cookie'));
        self::assertTrue($request5->getCookieParams()->has('cookie2'));
        self::assertTrue($request5->getCookieParams()->has('cookie3'));
        self::assertFalse($request5->getCookieParams()->has('cookie4'));
        self::assertFalse($request5->getCookieParams()->has('cookie5'));
        self::assertTrue($request5->getCookieParams()->has('cookie6'));

        self::assertSame('value', $request2->getCookieParams()->get('cookie'));
        self::assertSame('foo', $request2->getCookieParams()->get('cookie2'));
        self::assertSame('null', $request2->getCookieParams()->get('cookie3'));
        self::assertNull($request2->getCookieParams()->get('nonexistent'));
        self::assertNull($request2->getCookieParams()->get('nonexistentWithDefault'));

        self::assertSame('test', $request3->getCookieParams()->get('cookie4'));
        self::assertSame('value', $request3->getCookieParams()->get('cookie5'));

        self::assertSame('value', $request4->getCookieParams()->get('cookie'));
        self::assertSame('foo', $request4->getCookieParams()->get('cookie2'));
        self::assertSame('null', $request4->getCookieParams()->get('cookie3'));
        self::assertSame('null', $request4->getCookieParams()->get('cookie6'));

        self::assertSame('value', $request5->getCookieParams()->get('cookie'));
        self::assertSame('foo', $request5->getCookieParams()->get('cookie2'));
        self::assertSame('null', $request5->getCookieParams()->get('cookie3'));
        self::assertSame('value5', $request5->getCookieParams()->get('cookie6'));
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
            $request2->getQueryParams()->withAdded(['param3' => 'null'])
        );
        $request5 = $request2->withQueryParams(
            $request2->getQueryParams()->withAdded(['param3' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getQueryParams()->getAll());
        self::assertNotEmpty($request2->getQueryParams()->getAll());
        self::assertNotEmpty($request3->getQueryParams()->getAll());
        self::assertNotEmpty($request4->getQueryParams()->getAll());
        self::assertNotEmpty($request5->getQueryParams()->getAll());

        self::assertSame(
            array_filter(
                $queryParams,
                static fn (string|int $name): bool => $name === 'test2' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getQueryParams()->getOnly('test2', 'null')
        );
        self::assertSameCount(
            array_filter(
                $queryParams,
                static fn (string|int $name): bool => $name !== 'test2' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getQueryParams()->getAllExcept('test2', 'null')
        );

        self::assertTrue($request2->getQueryParams()->has('test'));
        self::assertTrue($request2->getQueryParams()->has('test2'));
        self::assertTrue($request2->getQueryParams()->has('bar'));
        self::assertTrue($request2->getQueryParams()->has('int'));
        self::assertTrue($request2->getQueryParams()->has('float'));
        self::assertFalse($request2->getQueryParams()->has('null'));
        self::assertTrue($request2->getQueryParams()->has(2));
        self::assertFalse($request2->getQueryParams()->has('nonexistent'));
        self::assertFalse($request2->getQueryParams()->has('param'));
        self::assertFalse($request2->getQueryParams()->has('param2'));
        self::assertFalse($request2->getQueryParams()->has('param3'));

        self::assertFalse($request3->getQueryParams()->has('test'));
        self::assertFalse($request3->getQueryParams()->has('test2'));
        self::assertFalse($request3->getQueryParams()->has('bar'));
        self::assertFalse($request3->getQueryParams()->has('int'));
        self::assertFalse($request3->getQueryParams()->has('float'));
        self::assertFalse($request3->getQueryParams()->has('null'));
        self::assertFalse($request3->getQueryParams()->has(2));
        self::assertTrue($request3->getQueryParams()->has('param'));
        self::assertTrue($request3->getQueryParams()->has('param2'));
        self::assertFalse($request3->getQueryParams()->has('param3'));

        self::assertTrue($request4->getQueryParams()->has('test'));
        self::assertTrue($request4->getQueryParams()->has('test2'));
        self::assertTrue($request4->getQueryParams()->has('bar'));
        self::assertTrue($request4->getQueryParams()->has('int'));
        self::assertTrue($request4->getQueryParams()->has('float'));
        self::assertFalse($request4->getQueryParams()->has('null'));
        self::assertTrue($request4->getQueryParams()->has(2));
        self::assertFalse($request4->getQueryParams()->has('param'));
        self::assertFalse($request4->getQueryParams()->has('param2'));
        self::assertTrue($request4->getQueryParams()->has('param3'));

        self::assertTrue($request5->getQueryParams()->has('test'));
        self::assertTrue($request5->getQueryParams()->has('test2'));
        self::assertTrue($request5->getQueryParams()->has('bar'));
        self::assertTrue($request5->getQueryParams()->has('int'));
        self::assertTrue($request5->getQueryParams()->has('float'));
        self::assertFalse($request5->getQueryParams()->has('null'));
        self::assertTrue($request5->getQueryParams()->has(2));
        self::assertFalse($request5->getQueryParams()->has('param'));
        self::assertFalse($request5->getQueryParams()->has('param2'));
        self::assertTrue($request5->getQueryParams()->has('param3'));

        self::assertSame('value', $request2->getQueryParams()->get('test'));
        self::assertSame('foo', $request2->getQueryParams()->get('test2'));
        self::assertInstanceOf(QueryParamCollection::class, $request2->getQueryParams()->get('bar'));
        self::assertSame('1', $request2->getQueryParams()->get('int'));
        self::assertSame('1.0', $request2->getQueryParams()->get('float'));
        self::assertNull($request2->getQueryParams()->get('null'));
        self::assertSame('number', $request2->getQueryParams()->get(2));
        self::assertNull($request2->getQueryParams()->get('nonexistentWithDefault'));

        self::assertSame('test', $request3->getQueryParams()->get('param'));
        self::assertSame('value', $request3->getQueryParams()->get('param2'));

        self::assertSame('value', $request4->getQueryParams()->get('test'));
        self::assertSame('foo', $request4->getQueryParams()->get('test2'));
        self::assertInstanceOf(QueryParamCollection::class, $request4->getQueryParams()->get('bar'));
        self::assertSame('1', $request4->getQueryParams()->get('int'));
        self::assertSame('1.0', $request4->getQueryParams()->get('float'));
        self::assertNull($request4->getQueryParams()->get('null'));
        self::assertSame('number', $request4->getQueryParams()->get(2));
        self::assertSame('null', $request4->getQueryParams()->get('param3'));

        self::assertSame('value', $request5->getQueryParams()->get('test'));
        self::assertSame('foo', $request5->getQueryParams()->get('test2'));
        self::assertInstanceOf(QueryParamCollection::class, $request5->getQueryParams()->get('bar'));
        self::assertSame('1', $request5->getQueryParams()->get('int'));
        self::assertSame('1.0', $request5->getQueryParams()->get('float'));
        self::assertNull($request5->getQueryParams()->get('null'));
        self::assertSame('number', $request5->getQueryParams()->get(2));
        self::assertSame('value5', $request5->getQueryParams()->get('param3'));
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
            $request2->getParsedBody()->withAdded(['param3' => 'null'])
        );
        $request5 = $request2->withParsedBody(
            $request2->getParsedBody()->withAdded(['param3' => 'value5'])
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getParsedBody()->getAll());
        self::assertNotEmpty($request2->getParsedBody()->getAll());
        self::assertNotEmpty($request3->getParsedBody()->getAll());
        self::assertNotEmpty($request4->getParsedBody()->getAll());
        self::assertNotEmpty($request5->getParsedBody()->getAll());

        self::assertSame(
            array_filter(
                $bodyParams,
                static fn (string|int $name): bool => $name === 'test2' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getParsedBody()->getOnly('test2', 'null')
        );
        self::assertSameCount(
            array_filter(
                $bodyParams,
                static fn (string|int $name): bool => $name !== 'test2' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->getParsedBody()->getAllExcept('test2', 'null')
        );

        self::assertTrue($request2->getParsedBody()->has('test'));
        self::assertTrue($request2->getParsedBody()->has('test2'));
        self::assertTrue($request2->getParsedBody()->has('bar'));
        self::assertTrue($request2->getParsedBody()->has('int'));
        self::assertTrue($request2->getParsedBody()->has('float'));
        self::assertFalse($request2->getParsedBody()->has('null'));
        self::assertTrue($request2->getParsedBody()->has(2));
        self::assertFalse($request2->getParsedBody()->has('nonexistent'));
        self::assertFalse($request2->getParsedBody()->has('param'));
        self::assertFalse($request2->getParsedBody()->has('param2'));
        self::assertFalse($request2->getParsedBody()->has('param3'));

        self::assertFalse($request3->getParsedBody()->has('test'));
        self::assertFalse($request3->getParsedBody()->has('test2'));
        self::assertFalse($request3->getParsedBody()->has('bar'));
        self::assertFalse($request3->getParsedBody()->has('int'));
        self::assertFalse($request3->getParsedBody()->has('float'));
        self::assertFalse($request3->getParsedBody()->has('null'));
        self::assertFalse($request3->getParsedBody()->has(2));
        self::assertTrue($request3->getParsedBody()->has('param'));
        self::assertTrue($request3->getParsedBody()->has('param2'));
        self::assertFalse($request3->getParsedBody()->has('param3'));

        self::assertTrue($request4->getParsedBody()->has('test'));
        self::assertTrue($request4->getParsedBody()->has('test2'));
        self::assertTrue($request4->getParsedBody()->has('bar'));
        self::assertTrue($request4->getParsedBody()->has('int'));
        self::assertTrue($request4->getParsedBody()->has('float'));
        self::assertFalse($request4->getParsedBody()->has('null'));
        self::assertTrue($request4->getParsedBody()->has(2));
        self::assertFalse($request4->getParsedBody()->has('param'));
        self::assertFalse($request4->getParsedBody()->has('param2'));
        self::assertTrue($request4->getParsedBody()->has('param3'));

        self::assertTrue($request5->getParsedBody()->has('test'));
        self::assertTrue($request5->getParsedBody()->has('test2'));
        self::assertTrue($request5->getParsedBody()->has('bar'));
        self::assertTrue($request5->getParsedBody()->has('int'));
        self::assertTrue($request5->getParsedBody()->has('float'));
        self::assertFalse($request5->getParsedBody()->has('null'));
        self::assertTrue($request5->getParsedBody()->has(2));
        self::assertFalse($request5->getParsedBody()->has('param'));
        self::assertFalse($request5->getParsedBody()->has('param2'));
        self::assertTrue($request5->getParsedBody()->has('param3'));

        self::assertSame('value', $request2->getParsedBody()->get('test'));
        self::assertSame('foo', $request2->getParsedBody()->get('test2'));
        self::assertInstanceOf(ParsedBodyParamCollection::class, $request2->getParsedBody()->get('bar'));
        self::assertSame('1', $request2->getParsedBody()->get('int'));
        self::assertSame('1.0', $request2->getParsedBody()->get('float'));
        self::assertNull($request2->getParsedBody()->get('null'));
        self::assertSame('number', $request2->getParsedBody()->get(2));
        self::assertNull($request2->getParsedBody()->get('nonexistentWithDefault'));

        self::assertSame('test', $request3->getParsedBody()->get('param'));
        self::assertSame('value', $request3->getParsedBody()->get('param2'));

        self::assertSame('value', $request4->getParsedBody()->get('test'));
        self::assertSame('foo', $request4->getParsedBody()->get('test2'));
        self::assertInstanceOf(ParsedBodyParamCollection::class, $request4->getParsedBody()->get('bar'));
        self::assertSame('1', $request4->getParsedBody()->get('int'));
        self::assertSame('1.0', $request4->getParsedBody()->get('float'));
        self::assertNull($request4->getParsedBody()->get('null'));
        self::assertSame('number', $request4->getParsedBody()->get(2));
        self::assertSame('null', $request4->getParsedBody()->get('param3'));

        self::assertSame('value', $request5->getParsedBody()->get('test'));
        self::assertSame('foo', $request5->getParsedBody()->get('test2'));
        self::assertInstanceOf(ParsedBodyParamCollection::class, $request5->getParsedBody()->get('bar'));
        self::assertSame('1', $request5->getParsedBody()->get('int'));
        self::assertSame('1.0', $request5->getParsedBody()->get('float'));
        self::assertNull($request5->getParsedBody()->get('null'));
        self::assertSame('number', $request5->getParsedBody()->get(2));
        self::assertSame('value5', $request5->getParsedBody()->get('param3'));
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
