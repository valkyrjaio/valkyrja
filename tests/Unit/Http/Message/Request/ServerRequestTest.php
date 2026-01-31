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
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_filter;

use const ARRAY_FILTER_USE_KEY;

class ServerRequestTest extends TestCase
{
    public function testServerParams(): void
    {
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            server: [
                'test'  => 'value',
                'test2' => 'foo',
                'bar'   => [],
                'int'   => 1,
                'float' => 1.0,
                'null'  => null,
            ]
        );

        self::assertNotSame($request, $request2);

        self::assertEmpty($request->getServerParams());
        self::assertNotEmpty($request2->getServerParams());

        self::assertTrue($request2->hasServerParam('test'));
        self::assertTrue($request2->hasServerParam('test2'));
        self::assertTrue($request2->hasServerParam('bar'));
        self::assertTrue($request2->hasServerParam('int'));
        self::assertTrue($request2->hasServerParam('float'));
        self::assertTrue($request2->hasServerParam('null'));
        self::assertFalse($request2->hasServerParam('nonexistent'));

        self::assertSame('value', $request2->getServerParam('test'));
        self::assertSame('foo', $request2->getServerParam('test2'));
        self::assertSame([], $request2->getServerParam('bar'));
        self::assertSame(1, $request2->getServerParam('int'));
        self::assertSame(1.0, $request2->getServerParam('float'));
        self::assertNull($request2->getServerParam('null'));
    }

    public function testCookies(): void
    {
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            cookies: [
                'cookie'  => 'value',
                'cookie2' => 'foo',
                'cookie3' => null,
            ]
        );
        $request3 = $request2->withCookieParams([
            'cookie4' => 'test',
            'cookie5' => 'value',
        ]);
        $request4 = $request2->withAddedCookieParam('cookie6', null);
        $request5 = $request2->withAddedCookieParam('cookie6', 'value5');

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getCookieParams());
        self::assertNotEmpty($request2->getCookieParams());
        self::assertNotEmpty($request3->getCookieParams());
        self::assertNotEmpty($request4->getCookieParams());
        self::assertNotEmpty($request5->getCookieParams());

        self::assertTrue($request2->hasCookieParam('cookie'));
        self::assertTrue($request2->hasCookieParam('cookie2'));
        self::assertTrue($request2->hasCookieParam('cookie3'));
        self::assertFalse($request2->hasCookieParam('cookie4'));
        self::assertFalse($request2->hasCookieParam('cookie5'));
        self::assertFalse($request2->hasCookieParam('cookie6'));

        self::assertFalse($request3->hasCookieParam('cookie'));
        self::assertFalse($request3->hasCookieParam('cookie2'));
        self::assertFalse($request3->hasCookieParam('cookie3'));
        self::assertTrue($request3->hasCookieParam('cookie4'));
        self::assertTrue($request3->hasCookieParam('cookie5'));

        self::assertTrue($request4->hasCookieParam('cookie'));
        self::assertTrue($request4->hasCookieParam('cookie2'));
        self::assertTrue($request4->hasCookieParam('cookie3'));
        self::assertFalse($request4->hasCookieParam('cookie4'));
        self::assertFalse($request4->hasCookieParam('cookie5'));
        self::assertTrue($request4->hasCookieParam('cookie6'));

        self::assertTrue($request5->hasCookieParam('cookie'));
        self::assertTrue($request5->hasCookieParam('cookie2'));
        self::assertTrue($request5->hasCookieParam('cookie3'));
        self::assertFalse($request5->hasCookieParam('cookie4'));
        self::assertFalse($request5->hasCookieParam('cookie5'));
        self::assertTrue($request5->hasCookieParam('cookie6'));

        self::assertSame('value', $request2->getCookieParam('cookie'));
        self::assertSame('foo', $request2->getCookieParam('cookie2'));
        self::assertNull($request2->getCookieParam('cookie3'));
        self::assertNull($request2->getCookieParam('nonexistent'));
        self::assertSame('default', $request2->getCookieParam('nonexistentWithDefault', 'default'));

        self::assertSame('test', $request3->getCookieParam('cookie4'));
        self::assertSame('value', $request3->getCookieParam('cookie5'));

        self::assertSame('value', $request4->getCookieParam('cookie'));
        self::assertSame('foo', $request4->getCookieParam('cookie2'));
        self::assertNull($request4->getCookieParam('cookie3'));
        self::assertNull($request4->getCookieParam('cookie6'));

        self::assertSame('value', $request5->getCookieParam('cookie'));
        self::assertSame('foo', $request5->getCookieParam('cookie2'));
        self::assertNull($request5->getCookieParam('cookie3'));
        self::assertSame('value5', $request5->getCookieParam('cookie6'));
    }

    public function testQuery(): void
    {
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            query: $queryParams = [
                'test'  => 'value',
                'test2' => 'foo',
                'bar'   => [],
                'int'   => 1,
                'float' => 1.0,
                'null'  => null,
                2       => 'number',
            ]
        );
        $request3 = $request2->withQueryParams([
            'param'  => 'test',
            'param2' => 'value',
        ]);
        $request4 = $request2->withAddedQueryParam('param3', null);
        $request5 = $request2->withAddedQueryParam('param3', 'value5');

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getQueryParams());
        self::assertNotEmpty($request2->getQueryParams());
        self::assertNotEmpty($request3->getQueryParams());
        self::assertNotEmpty($request4->getQueryParams());
        self::assertNotEmpty($request5->getQueryParams());

        self::assertSame(
            array_filter(
                $queryParams,
                static fn (string|int $name): bool => $name === 'test2' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->onlyQueryParams('test2', 'null')
        );
        self::assertSame(
            array_filter(
                $queryParams,
                static fn (string|int $name): bool => $name !== 'test2' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->exceptQueryParams('test2', 'null')
        );

        self::assertTrue($request2->hasQueryParam('test'));
        self::assertTrue($request2->hasQueryParam('test2'));
        self::assertTrue($request2->hasQueryParam('bar'));
        self::assertTrue($request2->hasQueryParam('int'));
        self::assertTrue($request2->hasQueryParam('float'));
        self::assertTrue($request2->hasQueryParam('null'));
        self::assertTrue($request2->hasQueryParam(2));
        self::assertFalse($request2->hasQueryParam('nonexistent'));
        self::assertFalse($request2->hasQueryParam('param'));
        self::assertFalse($request2->hasQueryParam('param2'));
        self::assertFalse($request2->hasQueryParam('param3'));

        self::assertFalse($request3->hasQueryParam('test'));
        self::assertFalse($request3->hasQueryParam('test2'));
        self::assertFalse($request3->hasQueryParam('bar'));
        self::assertFalse($request3->hasQueryParam('int'));
        self::assertFalse($request3->hasQueryParam('float'));
        self::assertFalse($request3->hasQueryParam('null'));
        self::assertFalse($request3->hasQueryParam(2));
        self::assertTrue($request3->hasQueryParam('param'));
        self::assertTrue($request3->hasQueryParam('param2'));
        self::assertFalse($request3->hasQueryParam('param3'));

        self::assertTrue($request4->hasQueryParam('test'));
        self::assertTrue($request4->hasQueryParam('test2'));
        self::assertTrue($request4->hasQueryParam('bar'));
        self::assertTrue($request4->hasQueryParam('int'));
        self::assertTrue($request4->hasQueryParam('float'));
        self::assertTrue($request4->hasQueryParam('null'));
        self::assertTrue($request4->hasQueryParam(2));
        self::assertFalse($request4->hasQueryParam('param'));
        self::assertFalse($request4->hasQueryParam('param2'));
        self::assertTrue($request4->hasQueryParam('param3'));

        self::assertTrue($request5->hasQueryParam('test'));
        self::assertTrue($request5->hasQueryParam('test2'));
        self::assertTrue($request5->hasQueryParam('bar'));
        self::assertTrue($request5->hasQueryParam('int'));
        self::assertTrue($request5->hasQueryParam('float'));
        self::assertTrue($request5->hasQueryParam('null'));
        self::assertTrue($request5->hasQueryParam(2));
        self::assertFalse($request5->hasQueryParam('param'));
        self::assertFalse($request5->hasQueryParam('param2'));
        self::assertTrue($request5->hasQueryParam('param3'));

        self::assertSame('value', $request2->getQueryParam('test'));
        self::assertSame('foo', $request2->getQueryParam('test2'));
        self::assertSame([], $request2->getQueryParam('bar'));
        self::assertSame(1, $request2->getQueryParam('int'));
        self::assertSame(1.0, $request2->getQueryParam('float'));
        self::assertNull($request2->getQueryParam('null'));
        self::assertSame('number', $request2->getQueryParam(2));
        self::assertSame('default', $request2->getQueryParam('nonexistentWithDefault', 'default'));

        self::assertSame('test', $request3->getQueryParam('param'));
        self::assertSame('value', $request3->getQueryParam('param2'));

        self::assertSame('value', $request4->getQueryParam('test'));
        self::assertSame('foo', $request4->getQueryParam('test2'));
        self::assertSame([], $request4->getQueryParam('bar'));
        self::assertSame(1, $request4->getQueryParam('int'));
        self::assertSame(1.0, $request4->getQueryParam('float'));
        self::assertNull($request4->getQueryParam('null'));
        self::assertSame('number', $request4->getQueryParam(2));
        self::assertNull($request4->getQueryParam('param3'));

        self::assertSame('value', $request5->getQueryParam('test'));
        self::assertSame('foo', $request5->getQueryParam('test2'));
        self::assertSame([], $request5->getQueryParam('bar'));
        self::assertSame(1, $request5->getQueryParam('int'));
        self::assertSame(1.0, $request5->getQueryParam('float'));
        self::assertNull($request5->getQueryParam('null'));
        self::assertSame('number', $request5->getQueryParam(2));
        self::assertSame('value5', $request5->getQueryParam('param3'));
    }

    public function testUploadedFiles(): void
    {
        $request  = new ServerRequest();
        $request2 = $request->withUploadedFiles([
            new UploadedFile(file: 'test'),
            new UploadedFile(file: 'test'),
        ]);
        $request3 = $request->withAddedUploadedFile(
            new UploadedFile(file: 'test')
        );

        self::assertNotSame($request, $request2);
        self::assertNotSame($request, $request3);

        self::assertEmpty($request->getUploadedFiles());
        self::assertNotEmpty($request2->getUploadedFiles());
        self::assertNotEmpty($request3->getUploadedFiles());

        self::assertCount(2, $request2->getUploadedFiles());
        self::assertCount(1, $request3->getUploadedFiles());
    }

    public function testParsedBody(): void
    {
        $request  = new ServerRequest();
        $request2 = new ServerRequest(
            parsedBody: $bodyParams = [
                'test'  => 'value',
                'test2' => 'foo',
                'bar'   => [],
                'int'   => 1,
                'float' => 1.0,
                'null'  => null,
                2       => 'number',
            ]
        );
        $request3 = $request2->withParsedBody([
            'param'  => 'test',
            'param2' => 'value',
        ]);
        $request4 = $request2->withAddedParsedBodyParam('param3', null);
        $request5 = $request2->withAddedParsedBodyParam('param3', 'value5');

        self::assertNotSame($request, $request2);
        self::assertNotSame($request2, $request3);
        self::assertNotSame($request2, $request4);
        self::assertNotSame($request2, $request5);

        self::assertEmpty($request->getParsedBody());
        self::assertNotEmpty($request2->getParsedBody());
        self::assertNotEmpty($request3->getParsedBody());
        self::assertNotEmpty($request4->getParsedBody());
        self::assertNotEmpty($request5->getParsedBody());

        self::assertSame(
            array_filter(
                $bodyParams,
                static fn (string|int $name): bool => $name === 'test2' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->onlyParsedBody('test2', 'null')
        );
        self::assertSame(
            array_filter(
                $bodyParams,
                static fn (string|int $name): bool => $name !== 'test2' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request2->exceptParsedBody('test2', 'null')
        );

        self::assertTrue($request2->hasParsedBodyParam('test'));
        self::assertTrue($request2->hasParsedBodyParam('test2'));
        self::assertTrue($request2->hasParsedBodyParam('bar'));
        self::assertTrue($request2->hasParsedBodyParam('int'));
        self::assertTrue($request2->hasParsedBodyParam('float'));
        self::assertTrue($request2->hasParsedBodyParam('null'));
        self::assertTrue($request2->hasParsedBodyParam(2));
        self::assertFalse($request2->hasParsedBodyParam('nonexistent'));
        self::assertFalse($request2->hasParsedBodyParam('param'));
        self::assertFalse($request2->hasParsedBodyParam('param2'));
        self::assertFalse($request2->hasParsedBodyParam('param3'));

        self::assertFalse($request3->hasParsedBodyParam('test'));
        self::assertFalse($request3->hasParsedBodyParam('test2'));
        self::assertFalse($request3->hasParsedBodyParam('bar'));
        self::assertFalse($request3->hasParsedBodyParam('int'));
        self::assertFalse($request3->hasParsedBodyParam('float'));
        self::assertFalse($request3->hasParsedBodyParam('null'));
        self::assertFalse($request3->hasParsedBodyParam(2));
        self::assertTrue($request3->hasParsedBodyParam('param'));
        self::assertTrue($request3->hasParsedBodyParam('param2'));
        self::assertFalse($request3->hasParsedBodyParam('param3'));

        self::assertTrue($request4->hasParsedBodyParam('test'));
        self::assertTrue($request4->hasParsedBodyParam('test2'));
        self::assertTrue($request4->hasParsedBodyParam('bar'));
        self::assertTrue($request4->hasParsedBodyParam('int'));
        self::assertTrue($request4->hasParsedBodyParam('float'));
        self::assertTrue($request4->hasParsedBodyParam('null'));
        self::assertTrue($request4->hasParsedBodyParam(2));
        self::assertFalse($request4->hasParsedBodyParam('param'));
        self::assertFalse($request4->hasParsedBodyParam('param2'));
        self::assertTrue($request4->hasParsedBodyParam('param3'));

        self::assertTrue($request5->hasParsedBodyParam('test'));
        self::assertTrue($request5->hasParsedBodyParam('test2'));
        self::assertTrue($request5->hasParsedBodyParam('bar'));
        self::assertTrue($request5->hasParsedBodyParam('int'));
        self::assertTrue($request5->hasParsedBodyParam('float'));
        self::assertTrue($request5->hasParsedBodyParam('null'));
        self::assertTrue($request5->hasParsedBodyParam(2));
        self::assertFalse($request5->hasParsedBodyParam('param'));
        self::assertFalse($request5->hasParsedBodyParam('param2'));
        self::assertTrue($request5->hasParsedBodyParam('param3'));

        self::assertSame('value', $request2->getParsedBodyParam('test'));
        self::assertSame('foo', $request2->getParsedBodyParam('test2'));
        self::assertSame([], $request2->getParsedBodyParam('bar'));
        self::assertSame(1, $request2->getParsedBodyParam('int'));
        self::assertSame(1.0, $request2->getParsedBodyParam('float'));
        self::assertNull($request2->getParsedBodyParam('null'));
        self::assertSame('number', $request2->getParsedBodyParam(2));
        self::assertSame('default', $request2->getParsedBodyParam('nonexistentWithDefault', 'default'));

        self::assertSame('test', $request3->getParsedBodyParam('param'));
        self::assertSame('value', $request3->getParsedBodyParam('param2'));

        self::assertSame('value', $request4->getParsedBodyParam('test'));
        self::assertSame('foo', $request4->getParsedBodyParam('test2'));
        self::assertSame([], $request4->getParsedBodyParam('bar'));
        self::assertSame(1, $request4->getParsedBodyParam('int'));
        self::assertSame(1.0, $request4->getParsedBodyParam('float'));
        self::assertNull($request4->getParsedBodyParam('null'));
        self::assertSame('number', $request4->getParsedBodyParam(2));
        self::assertNull($request4->getParsedBodyParam('param3'));

        self::assertSame('value', $request5->getParsedBodyParam('test'));
        self::assertSame('foo', $request5->getParsedBodyParam('test2'));
        self::assertSame([], $request5->getParsedBodyParam('bar'));
        self::assertSame(1, $request5->getParsedBodyParam('int'));
        self::assertSame(1.0, $request5->getParsedBodyParam('float'));
        self::assertNull($request5->getParsedBodyParam('null'));
        self::assertSame('number', $request5->getParsedBodyParam(2));
        self::assertSame('value5', $request5->getParsedBodyParam('param3'));
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
        $request2 = new ServerRequest(headers: [new Header(HeaderName::X_REQUESTED_WITH, 'XMLHttpRequest')]);
        $request3 = $request->withHeader(new Header(HeaderName::X_REQUESTED_WITH, 'XMLHttpRequest'));
        $request4 = $request->withoutHeader(HeaderName::X_REQUESTED_WITH);

        self::assertNotSame($request, $request2);
        self::assertNotSame($request, $request3);
        self::assertNotSame($request, $request4);

        self::assertFalse($request->isXmlHttpRequest());
        self::assertTrue($request2->isXmlHttpRequest());
        self::assertTrue($request3->isXmlHttpRequest());
        self::assertFalse($request4->isXmlHttpRequest());
    }
}
