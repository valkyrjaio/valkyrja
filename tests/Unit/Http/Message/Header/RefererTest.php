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

namespace Valkyrja\Tests\Unit\Http\Message\Header;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Referer;
use Valkyrja\Http\Message\Header\Value\Value;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class RefererTest extends TestCase
{
    public function testImplementsHeaderContract(): void
    {
        $referer = new Referer('https://example.com');

        self::assertInstanceOf(HeaderContract::class, $referer);
    }

    public function testExtendsHeader(): void
    {
        $referer = new Referer('https://example.com');

        self::assertInstanceOf(Header::class, $referer);
    }

    public function testHeaderNameIsReferer(): void
    {
        $referer = new Referer('https://example.com');

        self::assertSame(HeaderName::REFERER, $referer->getName());
    }

    public function testNormalizedNameIsReferer(): void
    {
        $referer = new Referer('https://example.com');

        self::assertSame('referer', $referer->getNormalizedName());
    }

    public function testWithSingleValue(): void
    {
        $url     = 'https://example.com/page';
        $referer = new Referer($url);

        self::assertCount(1, $referer->getValues());
        self::assertSame($url, $referer->getValuesAsString());
        self::assertSame(HeaderName::REFERER . ':' . $url, $referer->__toString());
    }

    public function testWithMultipleValues(): void
    {
        $url1    = 'https://example.com/page1';
        $url2    = 'https://example.com/page2';
        $referer = new Referer($url1, $url2);

        self::assertCount(2, $referer->getValues());
        self::assertSame("$url1,$url2", $referer->getValuesAsString());
    }

    public function testWithValueContract(): void
    {
        $value   = new Value('https://example.com');
        $referer = new Referer($value);

        self::assertCount(1, $referer->getValues());
        self::assertSame('https://example.com', $referer->getValuesAsString());
    }

    public function testWithMixedValues(): void
    {
        $value   = new Value('https://example.com/first');
        $referer = new Referer($value, 'https://example.com/second');

        self::assertCount(2, $referer->getValues());
        self::assertSame('https://example.com/first,https://example.com/second', $referer->getValuesAsString());
    }

    public function testToString(): void
    {
        $url     = 'https://example.com/path?query=value';
        $referer = new Referer($url);

        self::assertSame(HeaderName::REFERER . ':' . $url, $referer->__toString());
    }

    public function testWithValues(): void
    {
        $referer    = new Referer('https://original.com');
        $newReferer = $referer->withValues('https://new.com');

        self::assertNotSame($referer, $newReferer);
        self::assertSame('https://original.com', $referer->getValuesAsString());
        self::assertSame('https://new.com', $newReferer->getValuesAsString());
        self::assertSame(HeaderName::REFERER, $newReferer->getName());
    }

    public function testWithAddedValues(): void
    {
        $referer    = new Referer('https://original.com');
        $newReferer = $referer->withAddedValues('https://added.com');

        self::assertNotSame($referer, $newReferer);
        self::assertCount(1, $referer->getValues());
        self::assertCount(2, $newReferer->getValues());
        self::assertSame('https://original.com,https://added.com', $newReferer->getValuesAsString());
    }

    public function testJsonSerialize(): void
    {
        $url     = 'https://example.com';
        $referer = new Referer($url);

        self::assertSame(HeaderName::REFERER . ':' . $url, $referer->jsonSerialize());
    }

    public function testEmptyReferer(): void
    {
        $referer = new Referer();

        self::assertCount(0, $referer->getValues());
        self::assertSame('', $referer->getValuesAsString());
        self::assertSame(HeaderName::REFERER . ':', $referer->__toString());
    }
}
