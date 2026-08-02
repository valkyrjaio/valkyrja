<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Header;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Location;
use Valkyrja\Http\Message\Header\Value\Value;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LocationTest extends TestCase
{
    public function testImplementsHeaderContract(): void
    {
        self::assertInstanceOf(HeaderContract::class, new Location('https://example.com'));
    }

    public function testExtendsHeader(): void
    {
        self::assertInstanceOf(Header::class, new Location('https://example.com'));
    }

    public function testHeaderNameIsLocation(): void
    {
        $header = new Location('https://example.com');

        self::assertSame(HeaderName::LOCATION, $header->getName());
        self::assertSame('location', $header->getNormalizedName());
    }

    public function testWithSingleValue(): void
    {
        $url    = 'https://example.com/page';
        $header = new Location($url);

        self::assertCount(1, $header->getValues());
        self::assertSame($url, $header->getHeaderLine());
        self::assertSame(HeaderName::LOCATION . ': ' . $url, $header->__toString());
    }

    public function testWithMultipleValues(): void
    {
        $header = new Location('https://a.com', 'https://b.com');

        self::assertCount(2, $header->getValues());
        self::assertSame('https://a.com, https://b.com', $header->getHeaderLine());
    }

    public function testWithValueContract(): void
    {
        $header = new Location(new Value('https://example.com'));

        self::assertCount(1, $header->getValues());
        self::assertSame('https://example.com', $header->getHeaderLine());
    }

    public function testEmpty(): void
    {
        $header = new Location();

        self::assertCount(0, $header->getValues());
        self::assertSame('', $header->getHeaderLine());
    }
}
