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
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Value\Value;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ContentTypeTest extends TestCase
{
    public function testImplementsHeaderContract(): void
    {
        self::assertInstanceOf(HeaderContract::class, new ContentType('text/html'));
    }

    public function testExtendsHeader(): void
    {
        self::assertInstanceOf(Header::class, new ContentType('text/html'));
    }

    public function testHeaderNameIsContentType(): void
    {
        $header = new ContentType('text/html');

        self::assertSame(HeaderName::CONTENT_TYPE, $header->getName());
        self::assertSame('content-type', $header->getNormalizedName());
    }

    public function testWithSingleValue(): void
    {
        $header = new ContentType('application/json');

        self::assertCount(1, $header->getValues());
        self::assertSame('application/json', $header->getHeaderLine());
        self::assertSame(HeaderName::CONTENT_TYPE . ': application/json', $header->__toString());
    }

    public function testWithMultipleValues(): void
    {
        $header = new ContentType('text/html', 'charset=utf-8');

        self::assertCount(2, $header->getValues());
        self::assertSame('text/html, charset=utf-8', $header->getHeaderLine());
    }

    public function testWithValueContract(): void
    {
        $header = new ContentType(new Value('text/plain'));

        self::assertCount(1, $header->getValues());
        self::assertSame('text/plain', $header->getHeaderLine());
    }

    public function testEmpty(): void
    {
        $header = new ContentType();

        self::assertCount(0, $header->getValues());
        self::assertSame('', $header->getHeaderLine());
    }
}