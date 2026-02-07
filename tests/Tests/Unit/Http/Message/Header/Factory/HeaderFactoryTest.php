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

namespace Valkyrja\Tests\Unit\Http\Message\Header\Factory;

use Valkyrja\Http\Message\Header\Factory\HeaderFactory;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidNameException;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidValueException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HeaderFactoryTest extends TestCase
{
    public function testMarshalHeaders(): void
    {
        $server = [
            'REDIRECT_HTTP_TEST'        => 'REDIRECT_HTTP_TEST',
            'REDIRECT_HTTP_NO_OVERRIDE' => 'REDIRECT_HTTP_NO_OVERRIDE',
            'HTTP_NO_OVERRIDE'          => 'NO_OVERRIDE',
            'HTTP_SOMETHING'            => 'HTTP_SOMETHING',
            'HTTP_SOMETHING_ELSE'       => 'HTTP_SOMETHING_ELSE',
            'CONTENT_TYPE'              => 'CONTENT_TYPE',
            'BLAH'                      => 'BLAH',
        ];

        $headers = HeaderFactory::marshalHeaders($server);

        $expectedHeaders = [
            'test'           => 'REDIRECT_HTTP_TEST',
            'no-override'    => 'NO_OVERRIDE',
            'something'      => 'HTTP_SOMETHING',
            'something-else' => 'HTTP_SOMETHING_ELSE',
            'content-type'   => 'CONTENT_TYPE',
        ];

        self::assertSame($expectedHeaders['test'], $headers['test']->getValuesAsString());
        self::assertSame($expectedHeaders['no-override'], $headers['no-override']->getValuesAsString());
        self::assertSame($expectedHeaders['something'], $headers['something']->getValuesAsString());
        self::assertSame($expectedHeaders['something-else'], $headers['something-else']->getValuesAsString());
        self::assertSame($expectedHeaders['content-type'], $headers['content-type']->getValuesAsString());
    }

    public function testFilter(): void
    {
        self::assertSame('test', HeaderFactory::filterValue('test'));
        self::assertSame('test ', HeaderFactory::filterValue('test '));
        self::assertSame('test foo', HeaderFactory::filterValue('test foo'));
        self::assertSame('test foo', HeaderFactory::filterValue("test\n foo"));
        self::assertSame("test\r\n foo", HeaderFactory::filterValue("test\r\n foo"));
        self::assertSame("test\r\n   foo", HeaderFactory::filterValue("test\r\n   foo"));
        self::assertSame('test foo', HeaderFactory::filterValue("test foo\n"));
    }

    public function testInvalidHeaderValue(): void
    {
        $this->expectException(InvalidValueException::class);

        HeaderFactory::assertValidValue("\x0a");
    }

    public function testIsValid(): void
    {
        self::assertTrue(HeaderFactory::isValidValue('test'));
        self::assertTrue(HeaderFactory::isValidValue('Test'));
        self::assertTrue(HeaderFactory::isValidValue('Test-Header'));
        self::assertTrue(HeaderFactory::isValidValue('Test_Header'));

        self::assertFalse(HeaderFactory::isValidValue("\r"));
        self::assertFalse(HeaderFactory::isValidValue("\n"));
        self::assertFalse(HeaderFactory::isValidValue("\r\n"));
        self::assertFalse(HeaderFactory::isValidValue("\n\r"));
        self::assertTrue(HeaderFactory::isValidValue("\r\n "));
        self::assertTrue(HeaderFactory::isValidValue("\r\n  "));

        self::assertTrue(HeaderFactory::isValidValue("\x09"));
        self::assertFalse(HeaderFactory::isValidValue("\x0a"));
        self::assertFalse(HeaderFactory::isValidValue("\x0d"));
        self::assertTrue(HeaderFactory::isValidValue("\x80"));
        self::assertTrue(HeaderFactory::isValidValue("\xFE"));
        self::assertFalse(HeaderFactory::isValidValue("\x7F"));
        self::assertTrue(HeaderFactory::isValidValue("\x7E"));
    }

    public function testInvalidHeaderName(): void
    {
        $this->expectException(InvalidNameException::class);

        HeaderFactory::assertValidName(' ');
    }

    public function testIsValidName(): void
    {
        self::assertTrue(HeaderFactory::isValidName("a-zA-Z0-9'`#$%&*+.^_|~!-"));
        self::assertFalse(HeaderFactory::isValidName("\x00"));
        self::assertFalse(HeaderFactory::isValidName(':'));
        self::assertFalse(HeaderFactory::isValidName("\r\n"));
        self::assertFalse(HeaderFactory::isValidName("\n"));
        self::assertFalse(HeaderFactory::isValidName("test\n"));
        self::assertFalse(HeaderFactory::isValidName(' '));
    }

    public function testMarshalHeadersDoesNotSkipEmptyHttpValues(): void
    {
        $server = [
            'HTTP_EMPTY'     => '',
            'HTTP_NOT_EMPTY' => 'value',
        ];

        $headers = HeaderFactory::marshalHeaders($server);

        // Empty value is a valid header value
        self::assertArrayHasKey('empty', $headers);
        self::assertArrayHasKey('not-empty', $headers);
        self::assertSame('value', $headers['not-empty']->getValuesAsString());
    }

    public function testMarshalHeadersDoesNotSkipEmptyContentValues(): void
    {
        $server = [
            'CONTENT_TYPE'   => '',
            'CONTENT_LENGTH' => '100',
        ];

        $headers = HeaderFactory::marshalHeaders($server);

        // Empty value is a valid header value
        self::assertArrayHasKey('content-type', $headers);
        self::assertArrayHasKey('content-length', $headers);
        self::assertSame('100', $headers['content-length']->getValuesAsString());
    }

    public function testFilterValueRemovesDelCharacter(): void
    {
        // ASCII 127 is DEL character and should be removed
        self::assertSame('test', HeaderFactory::filterValue("te\x7Fst"));
    }

    public function testFilterValueRemovesNullByte(): void
    {
        // ASCII 255 (0xFF) should be removed
        self::assertSame('test', HeaderFactory::filterValue("te\xFFst"));
    }

    public function testFilterValueRemovesControlCharacters(): void
    {
        // Control characters below 32 (except tab at 9) should be removed
        self::assertSame('test', HeaderFactory::filterValue("te\x00st"));
        self::assertSame('test', HeaderFactory::filterValue("te\x01st"));
        self::assertSame('test', HeaderFactory::filterValue("te\x1Fst"));
    }

    public function testFilterValuePreservesTab(): void
    {
        // Tab (ASCII 9) should be preserved
        self::assertSame("te\tst", HeaderFactory::filterValue("te\tst"));
    }

    public function testFilterValuePreservesVisibleCharacters(): void
    {
        // Characters 32-126 and 128-254 should be preserved
        self::assertSame(' !"#$%&', HeaderFactory::filterValue(' !"#$%&'));
        self::assertSame('~', HeaderFactory::filterValue('~'));
        self::assertSame("\x80\xFE", HeaderFactory::filterValue("\x80\xFE"));
    }

    public function testAssertValidValueDoesNotThrowForValidValue(): void
    {
        HeaderFactory::assertValidValue('valid-value');
        HeaderFactory::assertValidValue("value\r\n with continuation");

        self::assertTrue(true); // If we reach here, no exception was thrown
    }

    public function testAssertValidNameDoesNotThrowForValidName(): void
    {
        HeaderFactory::assertValidName('Content-Type');
        HeaderFactory::assertValidName('X-Custom-Header');
        HeaderFactory::assertValidName("a-zA-Z0-9'`#\$%&*+.^_|~!-");

        self::assertTrue(true); // If we reach here, no exception was thrown
    }

    public function testMarshalHeadersWithRedirectThatHasOriginal(): void
    {
        // When REDIRECT_ prefixed version exists but original also exists,
        // the original should be used (not the redirect)
        $server = [
            'REDIRECT_HTTP_HOST' => 'redirect.example.com',
            'HTTP_HOST'          => 'original.example.com',
        ];

        $headers = HeaderFactory::marshalHeaders($server);

        self::assertArrayHasKey('host', $headers);
        self::assertSame('original.example.com', $headers['host']->getValuesAsString());
    }

    public function testFilterValueWithCrlfContinuation(): void
    {
        // CRLF followed by space or tab is a valid continuation and should be preserved
        self::assertSame("test\r\n value", HeaderFactory::filterValue("test\r\n value"));
        self::assertSame("test\r\n\tvalue", HeaderFactory::filterValue("test\r\n\tvalue"));
    }

    public function testFilterValueRemovesCrWithoutLf(): void
    {
        // CR not followed by LF should be removed
        self::assertSame('testvalue', HeaderFactory::filterValue("test\rvalue"));
    }
}
