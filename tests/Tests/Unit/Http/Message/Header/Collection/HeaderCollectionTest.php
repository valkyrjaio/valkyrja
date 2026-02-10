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

namespace Valkyrja\Tests\Unit\Http\Message\Header\Collection;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function strtolower;

final class HeaderCollectionTest extends TestCase
{
    protected HeaderCollection $headerData;

    protected Header $hostHeader;

    protected Header $contentTypeHeader;

    protected function setUp(): void
    {
        $this->hostHeader        = new Header(HeaderName::HOST, 'example.com');
        $this->contentTypeHeader = new Header(HeaderName::CONTENT_TYPE, 'application/json');
        $this->headerData        = new HeaderCollection($this->hostHeader, $this->contentTypeHeader);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(HeaderCollectionContract::class, $this->headerData);
    }

    public function testConstructorWithNoHeaders(): void
    {
        $headerData = new HeaderCollection();

        self::assertEmpty($headerData->getHeaders());
    }

    public function testConstructorNormalizesHeaderNames(): void
    {
        $headers = $this->headerData->getHeaders();

        self::assertArrayHasKey(strtolower(HeaderName::HOST), $headers);
        self::assertArrayHasKey(strtolower(HeaderName::CONTENT_TYPE), $headers);
    }

    public function testHasHeaderReturnsTrue(): void
    {
        self::assertTrue($this->headerData->hasHeader(HeaderName::HOST));
        self::assertTrue($this->headerData->hasHeader(HeaderName::CONTENT_TYPE));
    }

    public function testHasHeaderIsCaseInsensitive(): void
    {
        self::assertTrue($this->headerData->hasHeader('host'));
        self::assertTrue($this->headerData->hasHeader('HOST'));
        self::assertTrue($this->headerData->hasHeader('Host'));
    }

    public function testHasHeaderReturnsFalse(): void
    {
        self::assertFalse($this->headerData->hasHeader('X-Custom'));
    }

    public function testGetHeaderReturnsHeader(): void
    {
        $header = $this->headerData->getHeader(HeaderName::HOST);

        self::assertNotNull($header);
        self::assertSame(HeaderName::HOST, $header->getName());
    }

    public function testGetHeaderIsCaseInsensitive(): void
    {
        $header1 = $this->headerData->getHeader('host');
        $header2 = $this->headerData->getHeader('HOST');
        $header3 = $this->headerData->getHeader('Host');

        self::assertNotNull($header1);
        self::assertNotNull($header2);
        self::assertNotNull($header3);
        self::assertSame($header1, $header2);
        self::assertSame($header2, $header3);
    }

    public function testGetHeaderReturnsNullForMissing(): void
    {
        self::assertNull($this->headerData->getHeader('X-Custom'));
    }

    public function testGetHeaders(): void
    {
        $headers = $this->headerData->getHeaders();

        self::assertCount(2, $headers);
    }

    public function testOnlyHeaders(): void
    {
        $only = $this->headerData->onlyHeaders(strtolower(HeaderName::HOST));

        self::assertCount(1, $only);
        self::assertArrayHasKey(strtolower(HeaderName::HOST), $only);
        self::assertArrayNotHasKey(strtolower(HeaderName::CONTENT_TYPE), $only);
    }

    public function testOnlyHeadersWithNonexistentNames(): void
    {
        $only = $this->headerData->onlyHeaders('x-nonexistent');

        self::assertEmpty($only);
    }

    public function testExceptHeaders(): void
    {
        $except = $this->headerData->exceptHeaders(strtolower(HeaderName::HOST));

        self::assertCount(1, $except);
        self::assertArrayNotHasKey(strtolower(HeaderName::HOST), $except);
        self::assertArrayHasKey(strtolower(HeaderName::CONTENT_TYPE), $except);
    }

    public function testExceptHeadersWithNonexistentNames(): void
    {
        $except = $this->headerData->exceptHeaders('x-nonexistent');

        self::assertCount(2, $except);
    }

    public function testWithHeaderReturnsNewInstance(): void
    {
        $newHeader = new Header('X-Custom', 'value');
        $new       = $this->headerData->withHeader($newHeader);

        self::assertNotSame($this->headerData, $new);
        self::assertTrue($new->hasHeader('X-Custom'));
        self::assertCount(3, $new->getHeaders());
    }

    public function testWithHeaderDoesNotModifyOriginal(): void
    {
        $newHeader = new Header('X-Custom', 'value');
        $this->headerData->withHeader($newHeader);

        self::assertFalse($this->headerData->hasHeader('X-Custom'));
        self::assertCount(2, $this->headerData->getHeaders());
    }

    public function testWithHeaderOverridesExisting(): void
    {
        $replacement = new Header(HeaderName::HOST, 'new-host.com');
        $new         = $this->headerData->withHeader($replacement);

        self::assertCount(2, $new->getHeaders());

        $header = $new->getHeader(HeaderName::HOST);

        self::assertNotNull($header);
        self::assertSame('new-host.com', $header->getValuesAsString());
    }

    public function testWithHeadersReturnsNewInstance(): void
    {
        $header1 = new Header('X-First', 'one');
        $header2 = new Header('X-Second', 'two');
        $new     = $this->headerData->withHeaders($header1, $header2);

        self::assertNotSame($this->headerData, $new);
        self::assertCount(2, $new->getHeaders());
        self::assertTrue($new->hasHeader('X-First'));
        self::assertTrue($new->hasHeader('X-Second'));
        self::assertFalse($new->hasHeader(HeaderName::HOST));
    }

    public function testWithHeadersDoesNotModifyOriginal(): void
    {
        $header = new Header('X-Custom', 'value');
        $this->headerData->withHeaders($header);

        self::assertTrue($this->headerData->hasHeader(HeaderName::HOST));
        self::assertTrue($this->headerData->hasHeader(HeaderName::CONTENT_TYPE));
    }

    public function testWithAddedHeadersReturnsNewInstance(): void
    {
        $newHeader = new Header('X-Custom', 'value');
        $new       = $this->headerData->withAddedHeaders($newHeader);

        self::assertNotSame($this->headerData, $new);
        self::assertCount(3, $new->getHeaders());
        self::assertTrue($new->hasHeader(HeaderName::HOST));
        self::assertTrue($new->hasHeader(HeaderName::CONTENT_TYPE));
        self::assertTrue($new->hasHeader('X-Custom'));
    }

    public function testWithAddedHeadersMergesExisting(): void
    {
        $additionalHost = new Header(HeaderName::HOST, 'another.com');
        $new            = $this->headerData->withAddedHeaders($additionalHost);

        self::assertCount(2, $new->getHeaders());

        $header = $new->getHeader(HeaderName::HOST);

        self::assertNotNull($header);
        self::assertSame('example.com, another.com', $header->getValuesAsString());
    }

    public function testWithAddedHeadersDoesNotModifyOriginal(): void
    {
        $newHeader = new Header('X-Custom', 'value');
        $this->headerData->withAddedHeaders($newHeader);

        self::assertFalse($this->headerData->hasHeader('X-Custom'));
    }

    public function testWithoutHeadersReturnsNewInstance(): void
    {
        $new = $this->headerData->withoutHeaders(HeaderName::HOST);

        self::assertNotSame($this->headerData, $new);
        self::assertCount(1, $new->getHeaders());
        self::assertFalse($new->hasHeader(HeaderName::HOST));
        self::assertTrue($new->hasHeader(HeaderName::CONTENT_TYPE));
    }

    public function testWithoutHeadersDoesNotModifyOriginal(): void
    {
        $this->headerData->withoutHeaders(HeaderName::HOST);

        self::assertTrue($this->headerData->hasHeader(HeaderName::HOST));
        self::assertCount(2, $this->headerData->getHeaders());
    }

    public function testWithoutHeadersWithMultipleNames(): void
    {
        $new = $this->headerData->withoutHeaders(HeaderName::HOST, HeaderName::CONTENT_TYPE);

        self::assertEmpty($new->getHeaders());
    }

    public function testWithoutHeadersWithNonexistentName(): void
    {
        $new = $this->headerData->withoutHeaders('X-Nonexistent');

        self::assertCount(2, $new->getHeaders());
    }

    public function testFromArray(): void
    {
        $header     = new Header('X-Test', 'value');
        $headerData = $this->headerData->fromArray(['x-test' => $header]);

        self::assertNotNull($headerData->getHeader('X-Test'));
    }

    public function testFromArrayThrowsForInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->headerData->fromArray(['invalid' => 'not-a-header']);
    }

    public function testConstructorDeduplicatesByNormalizedName(): void
    {
        $host1      = new Header('Host', 'first.com');
        $host2      = new Header('Host', 'second.com');
        $headerData = new HeaderCollection($host1, $host2);

        self::assertCount(1, $headerData->getHeaders());

        $header = $headerData->getHeader('Host');

        self::assertNotNull($header);
        self::assertSame('second.com', $header->getValuesAsString());
    }
}
