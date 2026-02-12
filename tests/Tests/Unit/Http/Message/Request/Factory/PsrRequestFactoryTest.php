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

namespace Valkyrja\Tests\Unit\Http\Message\Request\Factory;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Http\Message\Request\Factory\PsrRequestFactory;
use Valkyrja\Http\Message\Request\Psr\ServerRequest as PsrServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class PsrRequestFactoryTest extends TestCase
{
    public function testFromPsr(): void
    {
        [$uploadedFile, $uploadedFile2] = $this->getUploadedFiles();

        $uriString = 'https://username:password@example.com:9090/path?arg=value#anchor';
        $request   = new ServerRequest(
            uri: UriFactory::fromString(uri: $uriString),
            method: RequestMethod::DELETE,
            body: $body             = new Stream(),
            headers: $headers       = new HeaderCollection(new Header('header1', 'test')),
            protocol: ProtocolVersion::V2,
            server: $server         = new ServerParamCollection(['VAR' => 'val']),
            cookies: $cookies       = new CookieParamCollection(['param' => 'cookies']),
            query: $query           = new QueryParamCollection(['param' => 'query']),
            parsedBody: $parsedBody = new ParsedBodyParamCollection(['param' => 'parsedBody']),
            files: new UploadedFileCollection([$uploadedFile, $uploadedFile2])
        );
        $body->write(string: $bodyContents = 'test');
        $body->rewind();

        $psrRequest = new PsrServerRequest($request);

        $fromPsr = PsrRequestFactory::fromPsr($psrRequest);

        self::assertCount(expectedCount: 2, haystack: $fromPsr->getUploadedFiles()->getFiles());
        self::assertInstanceOf(
            expected: UploadedFile::class,
            actual: $uploadedFileFromPsr = $fromPsr->getUploadedFiles()->getFile(0)
        );
        self::assertInstanceOf(
            expected: UploadedFile::class,
            actual: $uploadedFileFromPsr2 = $fromPsr->getUploadedFiles()->getFile(1)
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
            expected: 'example.com:9090',
            actual: $fromPsr->getHeaders()->getHeaderLine('Host')
        );
        self::assertSame(
            expected: $server->getParams(),
            actual: $fromPsr->getServerParams()->getParams()
        );
        self::assertSame(
            expected: $cookies->getParams(),
            actual: $fromPsr->getCookieParams()->getParams()
        );
        self::assertSame(
            expected: $query->getParams(),
            actual: $fromPsr->getQueryParams()->getParams()
        );
        self::assertSame(
            expected: $parsedBody->getParams(),
            actual: $fromPsr->getParsedBody()->getParams()
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
