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

namespace Valkyrja\Tests\Unit\Http\Message\Abstract;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Classes\Http\Message\MessageClass;
use Valkyrja\Tests\Unit\TestCase;

use function implode;

class MessageTest extends TestCase
{
    protected const string HEADER_NAME   = 'test';
    protected const string HEADER_VALUE  = 'foo';
    protected const string HEADER_VALUE2 = 'bar';

    protected static function assertEmptyHeaders(MessageClass $message): void
    {
        self::assertEmpty($message->getHeaders());
        self::assertEmpty($message->getHeader(self::HEADER_NAME));
        self::assertEmpty($message->getHeaderLine(self::HEADER_NAME));
    }

    protected static function assertNotEmptyHeaders(MessageClass $message): void
    {
        self::assertNotEmpty($message->getHeaders());
        self::assertNotEmpty($message->getHeader(self::HEADER_NAME));
        self::assertNotEmpty($message->getHeaderLine(self::HEADER_NAME));
    }

    protected static function assertHeaderValues(MessageClass $message, string ...$values): void
    {
        self::assertSame([self::HEADER_NAME => $values], $message->getHeaders());
        self::assertSame($values, $message->getHeader(self::HEADER_NAME));
        self::assertSame(implode(',', $values), $message->getHeaderLine(self::HEADER_NAME));
    }

    public function testProtocolVersion(): void
    {
        $message = new MessageClass();

        self::assertSame(ProtocolVersion::V1_1, $message->getProtocolVersion());

        $message2 = $message->withProtocolVersion(ProtocolVersion::V2);

        self::assertNotSame($message, $message2);
        self::assertSame(ProtocolVersion::V2, $message2->getProtocolVersion());

        $message3 = $message2->withProtocolVersion(ProtocolVersion::V3);

        self::assertNotSame($message, $message3);
        self::assertNotSame($message2, $message3);
        self::assertSame(ProtocolVersion::V3, $message3->getProtocolVersion());
    }

    public function testHeaders(): void
    {
        $message = new MessageClass();

        self::assertEmptyHeaders($message);

        $message2 = $message->withHeader(self::HEADER_NAME, self::HEADER_VALUE);

        self::assertNotSame($message, $message2);

        self::assertEmptyHeaders($message);
        self::assertNotEmptyHeaders($message2);

        self::assertHeaderValues($message2, self::HEADER_VALUE);

        $message3 = $message2->withAddedHeader(self::HEADER_NAME, self::HEADER_VALUE2);

        self::assertNotSame($message, $message2);
        self::assertNotSame($message2, $message3);

        self::assertEmptyHeaders($message);
        self::assertNotEmptyHeaders($message2);
        self::assertNotEmptyHeaders($message3);

        self::assertHeaderValues($message2, self::HEADER_VALUE);
        self::assertHeaderValues($message3, self::HEADER_VALUE, self::HEADER_VALUE2);

        $message4 = $message->withAddedHeader(self::HEADER_NAME, self::HEADER_VALUE2);

        self::assertNotSame($message, $message4);

        self::assertEmptyHeaders($message);
        self::assertNotEmptyHeaders($message4);

        self::assertHeaderValues($message4, self::HEADER_VALUE2);

        $message5 = $message2->withoutHeader(self::HEADER_NAME);
        $message6 = $message3->withoutHeader(self::HEADER_NAME);
        $message7 = $message4->withoutHeader(self::HEADER_NAME);
        $message8 = $message->withoutHeader(self::HEADER_NAME);

        self::assertNotSame($message, $message5);
        self::assertNotSame($message2, $message5);
        self::assertNotSame($message, $message6);
        self::assertNotSame($message3, $message6);
        self::assertNotSame($message, $message7);
        self::assertNotSame($message4, $message7);
        self::assertNotSame($message, $message8);

        self::assertEmptyHeaders($message5);
        self::assertEmptyHeaders($message6);
        self::assertEmptyHeaders($message7);
        self::assertEmptyHeaders($message8);

        $message9 = $message2->withHeader(self::HEADER_NAME, self::HEADER_VALUE2);

        self::assertNotSame($message, $message9);

        self::assertEmptyHeaders($message);
        self::assertNotEmptyHeaders($message9);

        self::assertHeaderValues($message9, self::HEADER_VALUE2);
    }

    public function testBody(): void
    {
        $message = new MessageClass();

        $message2 = $message->withBody(new Stream(PhpWrapper::input));

        self::assertNotSame($message, $message2);
        self::assertNotSame($message->getBody(), $message2->getBody());
    }

    public function testOverrideHeaders(): void
    {
        $testHeader         = 'test-header-override';
        $testHeaderOverride = 'test-header-override-override';

        $message = new MessageClass(
            headers: [
                'Test-Header'          => ['test-header-original'],
                'Test-Header-Override' => ['test-header-override-original'],
            ],
            testHeader: $testHeader,
            testHeaderOverride: $testHeaderOverride
        );

        // This shouldn't be overriden since injectHeader is called without the override flag
        self::assertNotSame('test-header-original', $message->getHeaderLine('Test-Header'));
        // This should be overriden since injectHeader is called with the override flag
        self::assertNotSame($testHeaderOverride, $message->getHeaderLine('Test-Header-Override'));
    }
}
