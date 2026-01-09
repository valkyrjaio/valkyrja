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

use UnexpectedValueException;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Factory\CookieFactory;
use Valkyrja\Http\Message\Factory\RequestFactory;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Request\Psr\ServerRequest as PsrServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_merge;

class RequestFactoryTest extends TestCase
{
    public function testFromGlobal(): void
    {
        $request = RequestFactory::fromGlobals();

        self::assertNotEmpty($request->getServerParams());
        self::assertNotEmpty($request->getHeaders());
        self::assertEmpty($request->getQueryParams());
        self::assertEmpty($request->getParsedBody());
        self::assertEmpty($request->getUploadedFiles());
        self::assertEmpty($request->getBody()->getContents());
        self::assertSame(expected: ProtocolVersion::V1_1, actual: $request->getProtocolVersion());
        self::assertSame(expected: RequestMethod::GET, actual: $request->getMethod());
    }

    public function testProtocolVersion(): void
    {
        $default  = RequestFactory::fromGlobals();
        $v1       = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => '1.0']);
        $v1_1     = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => '1.1']);
        $v2       = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => '2']);
        $v3       = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => '3']);
        $httpV1   = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => 'HTTP/1.0']);
        $httpV1_1 = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => 'HTTP/1.1']);
        $httpV2   = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => 'HTTP/2']);
        $httpV3   = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => 'HTTP/3']);

        self::assertSame(expected: ProtocolVersion::V1_1, actual: $default->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V1, actual: $v1->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V1_1, actual: $v1_1->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V2, actual: $v2->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V3, actual: $v3->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V1, actual: $httpV1->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V1_1, actual: $httpV1_1->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V2, actual: $httpV2->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V3, actual: $httpV3->getProtocolVersion());
    }

    public function testRequestMethod(): void
    {
        $default = RequestFactory::fromGlobals();
        $head    = RequestFactory::fromGlobals(server: ['REQUEST_METHOD' => RequestMethod::HEAD->value]);
        $post    = RequestFactory::fromGlobals(server: ['REQUEST_METHOD' => RequestMethod::POST->value]);
        $patch   = RequestFactory::fromGlobals(server: ['REQUEST_METHOD' => RequestMethod::PATCH->value]);
        $delete  = RequestFactory::fromGlobals(server: ['REQUEST_METHOD' => RequestMethod::DELETE->value]);

        self::assertSame(expected: RequestMethod::GET, actual: $default->getMethod());
        self::assertSame(expected: RequestMethod::HEAD, actual: $head->getMethod());
        self::assertSame(expected: RequestMethod::POST, actual: $post->getMethod());
        self::assertSame(expected: RequestMethod::PATCH, actual: $patch->getMethod());
        self::assertSame(expected: RequestMethod::DELETE, actual: $delete->getMethod());
    }

    public function testCookies(): void
    {
        $cookies = ['cookie' => 'value', 'cookie2' => 'value2'];

        $default           = RequestFactory::fromGlobals();
        $cookiesFromHeader = RequestFactory::fromGlobals(
            server: ['HTTP_COOKIE' => CookieFactory::convertCookieArrayToHeaderString($cookies)]
        );
        $cookiesPassedIn   = RequestFactory::fromGlobals(cookies: $cookies);

        self::assertEmpty($default->getCookieParams());
        self::assertSame(expected: $cookies, actual: $cookiesFromHeader->getCookieParams());
        self::assertSame(expected: 'value', actual: $cookiesFromHeader->getCookieParam(name: 'cookie'));
        self::assertSame(expected: 'value2', actual: $cookiesFromHeader->getCookieParam(name: 'cookie2'));
        self::assertSame(expected: $cookies, actual: $cookiesPassedIn->getCookieParams());
        self::assertSame(expected: 'value', actual: $cookiesPassedIn->getCookieParam(name: 'cookie'));
        self::assertSame(expected: 'value2', actual: $cookiesPassedIn->getCookieParam(name: 'cookie2'));
    }

    public function testFiles(): void
    {
        [$uploadedFile, $uploadedFile2] = $this->getUploadedFiles();

        $default       = RequestFactory::fromGlobals();
        $filesPassedIn = RequestFactory::fromGlobals(files: [$uploadedFile, $uploadedFile2]);

        self::assertEmpty($default->getUploadedFiles());
        self::assertCount(expectedCount: 2, haystack: $filesPassedIn->getUploadedFiles());
        self::assertInstanceOf(
            expected: UploadedFile::class,
            actual: $uploadedFileFromGlobal = $filesPassedIn->getUploadedFiles()[0]
        );
        self::assertInstanceOf(
            expected: UploadedFile::class,
            actual: $uploadedFileFromGlobal2 = $filesPassedIn->getUploadedFiles()[1]
        );
        self::assertSame(
            expected: $uploadedFile->getStream()->__toString(),
            actual: $uploadedFileFromGlobal->getStream()->__toString()
        );
        self::assertSame(
            expected: $uploadedFile2->getStream()->__toString(),
            actual: $uploadedFileFromGlobal2->getStream()->__toString()
        );
        self::assertSame(
            expected: $uploadedFile->getSize(),
            actual: $uploadedFileFromGlobal->getSize()
        );
        self::assertSame(
            expected: $uploadedFile2->getSize(),
            actual: $uploadedFileFromGlobal2->getSize()
        );
        self::assertSame(
            expected: $uploadedFile->getError(),
            actual: $uploadedFileFromGlobal->getError()
        );
        self::assertSame(
            expected: $uploadedFile2->getError(),
            actual: $uploadedFileFromGlobal2->getError()
        );
        self::assertSame(
            expected: $uploadedFile->getClientFilename(),
            actual: $uploadedFileFromGlobal->getClientFilename()
        );
        self::assertSame(
            expected: $uploadedFile2->getClientFilename(),
            actual: $uploadedFileFromGlobal2->getClientFilename()
        );
        self::assertSame(
            expected: $uploadedFile->getClientMediaType(),
            actual: $uploadedFileFromGlobal->getClientMediaType()
        );
        self::assertSame(
            expected: $uploadedFile2->getClientMediaType(),
            actual: $uploadedFileFromGlobal2->getClientMediaType()
        );
    }

    public function testProtocolVersionInvalidValue(): void
    {
        $this->expectException(UnexpectedValueException::class);

        RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => 'HTTP/invalid']);
    }

    public function testJsonFromGlobals(): void
    {
        $request = RequestFactory::jsonFromGlobals();

        self::assertInstanceOf(
            expected: JsonServerRequest::class,
            actual: $request
        );
    }

    public function testFromPsr(): void
    {
        [$uploadedFile, $uploadedFile2] = $this->getUploadedFiles();

        $uriString = 'https://username:password@example.com:9090/path?arg=value#anchor';
        $request   = new ServerRequest(
            uri: Uri::fromString(uri: $uriString),
            method: RequestMethod::DELETE,
            body: $body             = new Stream(),
            headers: $headers       = ['header1' => ['test']],
            server: $server         = ['VAR' => 'val'],
            cookies: $cookies       = ['param' => 'cookies'],
            query: $query           = ['param' => 'query'],
            parsedBody: $parsedBody = ['param' => 'parsedBody'],
            protocol: ProtocolVersion::V2,
            files: [$uploadedFile, $uploadedFile2]
        );
        $body->write(string: $bodyContents = 'test');
        $body->rewind();

        $psrRequest = new PsrServerRequest($request);

        $fromPsr = RequestFactory::fromPsr($psrRequest);

        self::assertCount(expectedCount: 2, haystack: $fromPsr->getUploadedFiles());
        self::assertInstanceOf(
            expected: UploadedFile::class,
            actual: $uploadedFileFromPsr = $fromPsr->getUploadedFiles()[0]
        );
        self::assertInstanceOf(
            expected: UploadedFile::class,
            actual: $uploadedFileFromPsr2 = $fromPsr->getUploadedFiles()[1]
        );
        self::assertSame(
            expected: $uploadedFile->getStream()->__toString(),
            actual: $uploadedFileFromPsr->getStream()->__toString()
        );
        self::assertSame(
            expected: $uploadedFile2->getStream()->__toString(),
            actual: $uploadedFileFromPsr2->getStream()->__toString()
        );
        self::assertSame(
            expected: $uploadedFile->getSize(),
            actual: $uploadedFileFromPsr->getSize()
        );
        self::assertSame(
            expected: $uploadedFile2->getSize(),
            actual: $uploadedFileFromPsr2->getSize()
        );
        self::assertSame(
            expected: $uploadedFile->getError(),
            actual: $uploadedFileFromPsr->getError()
        );
        self::assertSame(
            expected: $uploadedFile2->getError(),
            actual: $uploadedFileFromPsr2->getError()
        );
        self::assertSame(
            expected: $uploadedFile->getClientFilename(),
            actual: $uploadedFileFromPsr->getClientFilename()
        );
        self::assertSame(
            expected: $uploadedFile2->getClientFilename(),
            actual: $uploadedFileFromPsr2->getClientFilename()
        );
        self::assertSame(
            expected: $uploadedFile->getClientMediaType(),
            actual: $uploadedFileFromPsr->getClientMediaType()
        );
        self::assertSame(
            expected: $uploadedFile2->getClientMediaType(),
            actual: $uploadedFileFromPsr2->getClientMediaType()
        );
        self::assertSame(
            expected: $uriString,
            actual: $fromPsr->getUri()->__toString()
        );
        self::assertSame(
            expected: array_merge(
                $headers,
                [
                    'Host' => ['example.com:9090'],
                ]
            ),
            actual: $fromPsr->getHeaders()
        );
        self::assertSame(
            expected: $server,
            actual: $fromPsr->getServerParams()
        );
        self::assertSame(
            expected: $cookies,
            actual: $fromPsr->getCookieParams()
        );
        self::assertSame(
            expected: $query,
            actual: $fromPsr->getQueryParams()
        );
        self::assertSame(
            expected: $parsedBody,
            actual: $fromPsr->getParsedBody()
        );
        self::assertSame(
            expected: $bodyContents,
            actual: $fromPsr->getBody()->getContents()
        );
    }

    protected function getUploadedFiles(): array
    {
        $stream = new Stream();
        $stream->write(string: 'test');
        $stream->rewind();

        $stream2 = new Stream();
        $stream2->write(string: 'test2');
        $stream2->rewind();

        $uploadedFile = new UploadedFile(
            stream: $stream,
            uploadError: UploadError::OK,
            size: 1,
            fileName: 'test',
            mediaType: 'txt',
        );

        $uploadedFile2 = new UploadedFile(
            stream: $stream2,
            uploadError: UploadError::OK,
            size: 2,
            fileName: 'test2',
            mediaType: 'txt',
        );

        return [$uploadedFile, $uploadedFile2];
    }
}
