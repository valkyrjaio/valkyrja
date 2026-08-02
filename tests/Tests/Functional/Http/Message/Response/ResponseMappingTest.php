<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Http\Message\Response;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Enum\StatusText;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Functional\Abstract\TestCase;

use function array_keys;
use function constant;

/**
 * Message-mapping fidelity for an outgoing HTTP response.
 *
 * Asserts that a status code, headers, and body land on the framework's own
 * Response object and round-trip back out unchanged — including the
 * StatusCode to reason-phrase mapping across every defined code.
 */
final class ResponseMappingTest extends TestCase
{
    /**
     * Every status code the StatusCode enum defines.
     *
     * @return array<string, array{StatusCode}>
     */
    public static function provideStatusCodes(): array
    {
        $codes = [];

        foreach (StatusCode::cases() as $case) {
            $codes[$case->name] = [$case];
        }

        return $codes;
    }

    /**
     * A representative code from each status class.
     *
     * @return array<string, array{StatusCode, int, string}>
     */
    public static function provideRepresentativeStatusCodes(): array
    {
        return [
            'informational' => [StatusCode::CONTINUE, 100, 'Continue'],
            'successful'    => [StatusCode::OK, 200, 'OK'],
            'created'       => [StatusCode::CREATED, 201, 'Created'],
            'no content'    => [StatusCode::NO_CONTENT, 204, 'No Content'],
            'redirection'   => [StatusCode::MOVED_PERMANENTLY, 301, 'Moved Permanently'],
            'not modified'  => [StatusCode::NOT_MODIFIED, 304, 'Not Modified'],
            'client error'  => [StatusCode::NOT_FOUND, 404, 'Not Found'],
            'unauthorized'  => [StatusCode::UNAUTHORIZED, 401, 'Unauthorized'],
            'server error'  => [StatusCode::INTERNAL_SERVER_ERROR, 500, 'Internal Server Error'],
        ];
    }

    /**
     * Every status code resolves to the reason phrase its StatusText twin defines.
     */
    #[DataProvider('provideStatusCodes')]
    public function testEveryStatusCodeMapsToItsReasonPhrase(StatusCode $statusCode): void
    {
        /** @var StatusText $statusText */
        $statusText = constant(StatusText::class . '::' . $statusCode->name);

        $response = new Response(statusCode: $statusCode);

        self::assertSame(expected: $statusCode, actual: $response->getStatusCode());
        self::assertSame(expected: $statusCode->value, actual: $response->getStatusCode()->value);
        self::assertSame(expected: $statusText->value, actual: $statusCode->asPhrase());
        self::assertSame(expected: $statusText->value, actual: $response->getReasonPhrase());
    }

    /**
     * A representative code from each class exposes its numeric code and phrase.
     */
    #[DataProvider('provideRepresentativeStatusCodes')]
    public function testRepresentativeStatusCodesMapOntoResponse(
        StatusCode $statusCode,
        int $expectedCode,
        string $expectedPhrase
    ): void {
        $response = new Response(statusCode: $statusCode);

        self::assertSame(expected: $expectedCode, actual: $response->getStatusCode()->value);
        self::assertSame(expected: $expectedCode, actual: $statusCode->code());
        self::assertSame(expected: $expectedPhrase, actual: $response->getReasonPhrase());
    }

    /**
     * Swapping the status code swaps the reason phrase with it.
     */
    public function testWithStatusCodeUpdatesTheReasonPhrase(): void
    {
        $response = new Response(statusCode: StatusCode::OK);
        $new      = $response->withStatusCode(StatusCode::IM_USED);

        self::assertSame(expected: StatusCode::OK, actual: $response->getStatusCode());
        self::assertSame(expected: 'OK', actual: $response->getReasonPhrase());
        self::assertSame(expected: StatusCode::IM_USED, actual: $new->getStatusCode());
        self::assertSame(expected: 'IM Used', actual: $new->getReasonPhrase());
    }

    /**
     * A custom reason phrase overrides the default without changing the code,
     * and an empty phrase restores the code's own phrase.
     */
    public function testCustomReasonPhraseOverridesTheDefault(): void
    {
        $response = new Response(statusCode: StatusCode::NOT_FOUND);
        $custom   = $response->withReasonPhrase('Totally Missing');
        $restored = $custom->withReasonPhrase('');

        self::assertSame(expected: 'Not Found', actual: $response->getReasonPhrase());
        self::assertSame(expected: 'Totally Missing', actual: $custom->getReasonPhrase());
        self::assertSame(expected: StatusCode::NOT_FOUND, actual: $custom->getStatusCode());
        self::assertSame(expected: 'Not Found', actual: $restored->getReasonPhrase());
    }

    /**
     * Headers supplied to the constructor round-trip back out, case-insensitively.
     */
    public function testHeadersRoundTripThroughTheResponse(): void
    {
        $response = new Response(
            statusCode: StatusCode::OK,
            headers: new HeaderCollection(
                new Header('Content-Type', 'application/json'),
                new Header('Cache-Control', 'no-cache', 'no-store')
            )
        );

        $headers = $response->getHeaders();

        self::assertSame(expected: ['content-type', 'cache-control'], actual: array_keys($headers->getAll()));
        self::assertTrue($headers->has('CONTENT-TYPE'));
        self::assertSame(expected: 'application/json', actual: $headers->getHeaderLine('Content-Type'));
        self::assertSame(expected: 'no-cache, no-store', actual: $headers->getHeaderLine('cache-control'));
        self::assertSame(
            expected: ['no-cache', 'no-store'],
            actual: $headers->get('Cache-Control')->getValues()
        );

        $added = $response->withHeaders(
            $headers->withAddedHeaders(new Header('CACHE-CONTROL', 'must-revalidate'))
        );

        self::assertSame(
            expected: 'no-cache, no-store, must-revalidate',
            actual: $added->getHeaders()->getHeaderLine('Cache-Control')
        );
        self::assertSame(expected: 'no-cache, no-store', actual: $response->getHeaders()->getHeaderLine('Cache-Control'));

        $removed = $response->withHeaders($headers->withoutHeader('Content-Type'));

        self::assertFalse($removed->getHeaders()->has('content-type'));
        self::assertSame(expected: '', actual: $removed->getHeaders()->getHeaderLine('Content-Type'));
    }

    /**
     * A body supplied to the constructor round-trips back out unchanged.
     */
    public function testBodyRoundTripsThroughTheConstructor(): void
    {
        $body = new Stream();
        $body->write('{"ok":true}');
        $body->rewind();

        $response = new Response($body, StatusCode::OK);

        self::assertSame(expected: '{"ok":true}', actual: $response->getBody()->getContents());
        self::assertSame(expected: '{"ok":true}', actual: $response->getBody()->__toString());
    }

    /**
     * The create() factory writes content into a rewound body stream.
     */
    public function testBodyRoundTripsThroughTheCreateFactory(): void
    {
        $response = Response::create(
            content: 'plain content',
            statusCode: StatusCode::ACCEPTED,
            headers: new HeaderCollection(new Header('X-Trace', 'abc'))
        );

        self::assertSame(expected: 'plain content', actual: $response->getBody()->getContents());
        self::assertSame(expected: StatusCode::ACCEPTED, actual: $response->getStatusCode());
        self::assertSame(expected: 'Accepted', actual: $response->getReasonPhrase());
        self::assertSame(expected: 'abc', actual: $response->getHeaders()->getHeaderLine('x-trace'));
    }

    /**
     * create() with no arguments yields an empty 200 response.
     */
    public function testCreateDefaults(): void
    {
        $response = Response::create();

        self::assertSame(expected: '', actual: $response->getBody()->getContents());
        self::assertSame(expected: StatusCode::OK, actual: $response->getStatusCode());
        self::assertSame(expected: 'OK', actual: $response->getReasonPhrase());
        self::assertSame(expected: [], actual: $response->getHeaders()->getAll());
        self::assertSame(expected: ProtocolVersion::V1_1, actual: $response->getProtocolVersion());
    }

    /**
     * Swapping the body leaves the original response untouched.
     */
    public function testWithBodyLeavesTheOriginalUntouched(): void
    {
        $response = Response::create(content: 'original');

        $replacement = new Stream();
        $replacement->write('replacement');
        $replacement->rewind();

        $new = $response->withBody($replacement);

        self::assertSame(expected: 'replacement', actual: $new->getBody()->__toString());
        self::assertSame(expected: 'original', actual: $response->getBody()->__toString());
    }

    /**
     * The protocol version round-trips.
     */
    public function testProtocolVersionRoundTrips(): void
    {
        $response = Response::create();
        $new      = $response->withProtocolVersion(ProtocolVersion::V2);

        self::assertSame(expected: ProtocolVersion::V1_1, actual: $response->getProtocolVersion());
        self::assertSame(expected: ProtocolVersion::V2, actual: $new->getProtocolVersion());
    }
}
