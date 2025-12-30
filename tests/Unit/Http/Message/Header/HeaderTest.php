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

use JsonException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidNameException;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidValueException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetSetException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetUnsetException;
use Valkyrja\Tests\Unit\TestCase;

use function implode;
use function json_encode;
use function strtolower;

use const JSON_THROW_ON_ERROR;

class HeaderTest extends TestCase
{
    public function testFromValue(): void
    {
        $header  = Header::fromValue(HeaderName::HOST);
        $header2 = Header::fromValue(HeaderName::HOST . ':test');
        $header3 = Header::fromValue(HeaderName::HOST . ':test,foo,bar');

        self::assertSame(HeaderName::HOST, $header->getName());
        self::assertSame(strtolower(HeaderName::HOST), $header->getNormalizedName());
        self::assertEmpty($header->getValues());
        self::assertEmpty($header->getValuesAsString());
        self::assertSame(HeaderName::HOST . ':', $header->__toString());

        self::assertSame(HeaderName::HOST, $header2->getName());
        self::assertSame(strtolower(HeaderName::HOST), $header2->getNormalizedName());
        self::assertCount(1, $header2->getValues());
        self::assertSame('test', $header2->getValuesAsString());
        self::assertSame(HeaderName::HOST . ':test', $header2->__toString());

        self::assertSame(HeaderName::HOST, $header3->getName());
        self::assertSame(strtolower(HeaderName::HOST), $header3->getNormalizedName());
        self::assertCount(3, $header3->getValues());
        self::assertSame('test,foo,bar', $header3->getValuesAsString());
        self::assertSame(HeaderName::HOST . ':test,foo,bar', $header3->__toString());
    }

    public function testName(): void
    {
        $host        = Header::fromValue(HeaderName::HOST);
        $contentType = Header::fromValue(HeaderName::CONTENT_TYPE);

        $pragma = $host->withName(HeaderName::PRAGMA);

        self::assertNotSame($host, $pragma);

        self::assertSame(HeaderName::HOST, $host->getName());
        self::assertSame(strtolower(HeaderName::HOST), $host->getNormalizedName());

        self::assertSame(HeaderName::CONTENT_TYPE, $contentType->getName());
        self::assertSame(strtolower(HeaderName::CONTENT_TYPE), $contentType->getNormalizedName());

        self::assertSame(HeaderName::PRAGMA, $pragma->getName());
        self::assertSame(strtolower(HeaderName::PRAGMA), $pragma->getNormalizedName());
    }

    /**
     * @throws JsonException
     */
    public function testValue(): void
    {
        $value       = ['test'];
        $value2      = ['test2', 'test3'];
        $value3      = ['foo'];
        $value4      = ['bar', 'bar2'];
        $addedValue  = ['bar3'];
        $addedValue2 = ['foo2', 'foo3'];

        $valueString       = implode(',', $value);
        $value2String      = implode(',', $value2);
        $value3String      = implode(',', $value3);
        $value4String      = implode(',', $value4);
        $addedValueString  = implode(',', $addedValue);
        $addedValue2String = implode(',', $addedValue2);

        $singleValue = new Header(HeaderName::HOST, ...$value);
        $multiValue  = new Header(HeaderName::CONTENT_TYPE, ...$value2);

        $withSingleValue = $singleValue->withValues(...$value3);
        $withMultiValue  = $singleValue->withValues(...$value4);

        $addedSingleValue = $singleValue->withAddedValues(...$addedValue);
        $addedMultiValue  = $singleValue->withAddedValues(...$addedValue2);

        $singleValueToString      = HeaderName::HOST . ":$valueString";
        $multiValueToString       = HeaderName::CONTENT_TYPE . ":$value2String";
        $withSingleValueToString  = HeaderName::HOST . ":$value3String";
        $withMultiValueToString   = HeaderName::HOST . ":$value4String";
        $addedSingleValueToString = HeaderName::HOST . ":$valueString,$addedValueString";
        $addedMultiValueToString  = HeaderName::HOST . ":$valueString,$addedValue2String";

        self::assertNotSame($singleValue, $withSingleValue);
        self::assertNotSame($singleValue, $withMultiValue);
        self::assertNotSame($singleValue, $addedSingleValue);
        self::assertNotSame($singleValue, $addedMultiValue);

        self::assertCount(1, $singleValue->getValues());
        self::assertCount(1, $singleValue);
        self::assertSame(1, $singleValue->count());
        self::assertSame($valueString, $singleValue->getValuesAsString());
        self::assertSame($singleValueToString, $singleValue->asValue());
        self::assertSame($singleValueToString, $singleValue->asFlatValue());
        self::assertSame($singleValueToString, $singleValue->__toString());
        self::assertSame($singleValueToString, $singleValue->jsonSerialize());
        self::assertSame("\"$singleValueToString\"", json_encode($singleValue, JSON_THROW_ON_ERROR));

        self::assertCount(2, $multiValue->getValues());
        self::assertCount(2, $multiValue);
        self::assertSame(2, $multiValue->count());
        self::assertSame($value2String, $multiValue->getValuesAsString());
        self::assertSame($multiValueToString, $multiValue->asValue());
        self::assertSame($multiValueToString, $multiValue->asFlatValue());
        self::assertSame($multiValueToString, $multiValue->__toString());
        self::assertSame($multiValueToString, $multiValue->jsonSerialize());
        self::assertSame("\"$multiValueToString\"", json_encode($multiValue, JSON_THROW_ON_ERROR));

        self::assertCount(1, $withSingleValue->getValues());
        self::assertCount(1, $withSingleValue);
        self::assertSame(1, $withSingleValue->count());
        self::assertSame($value3String, $withSingleValue->getValuesAsString());
        self::assertSame($withSingleValueToString, $withSingleValue->asValue());
        self::assertSame($withSingleValueToString, $withSingleValue->asFlatValue());
        self::assertSame($withSingleValueToString, $withSingleValue->__toString());
        self::assertSame($withSingleValueToString, $withSingleValue->jsonSerialize());
        self::assertSame("\"$withSingleValueToString\"", json_encode($withSingleValue, JSON_THROW_ON_ERROR));

        self::assertCount(2, $withMultiValue->getValues());
        self::assertCount(2, $withMultiValue);
        self::assertSame(2, $withMultiValue->count());
        self::assertSame($value4String, $withMultiValue->getValuesAsString());
        self::assertSame($withMultiValueToString, $withMultiValue->asValue());
        self::assertSame($withMultiValueToString, $withMultiValue->asFlatValue());
        self::assertSame($withMultiValueToString, $withMultiValue->__toString());
        self::assertSame($withMultiValueToString, $withMultiValue->jsonSerialize());
        self::assertSame("\"$withMultiValueToString\"", json_encode($withMultiValue, JSON_THROW_ON_ERROR));

        self::assertCount(2, $addedSingleValue->getValues());
        self::assertCount(2, $addedSingleValue);
        self::assertSame(2, $addedSingleValue->count());
        self::assertSame("$valueString,$addedValueString", $addedSingleValue->getValuesAsString());
        self::assertSame($addedSingleValueToString, $addedSingleValue->asValue());
        self::assertSame($addedSingleValueToString, $addedSingleValue->asFlatValue());
        self::assertSame($addedSingleValueToString, $addedSingleValue->__toString());
        self::assertSame($addedSingleValueToString, $addedSingleValue->jsonSerialize());
        self::assertSame("\"$addedSingleValueToString\"", json_encode($addedSingleValue, JSON_THROW_ON_ERROR));

        self::assertCount(3, $addedMultiValue->getValues());
        self::assertCount(3, $addedMultiValue);
        self::assertSame(3, $addedMultiValue->count());
        self::assertSame("$valueString,$addedValue2String", $addedMultiValue->getValuesAsString());
        self::assertSame($addedMultiValueToString, $addedMultiValue->asValue());
        self::assertSame($addedMultiValueToString, $addedMultiValue->asFlatValue());
        self::assertSame($addedMultiValueToString, $addedMultiValue->__toString());
        self::assertSame($addedMultiValueToString, $addedMultiValue->jsonSerialize());
        self::assertSame("\"$addedMultiValueToString\"", json_encode($addedMultiValue, JSON_THROW_ON_ERROR));
    }

    public function testIteration(): void
    {
        $values = ['test', 'foo', 'bar'];

        $header = new Header(HeaderName::HOST, ...$values);

        self::assertCount(3, $header);
        self::assertSame(3, $header->count());
        self::assertSame(0, $header->key());

        foreach ($header as $key => $value) {
            self::assertSame($key, $header->key());
            self::assertTrue($header->valid());
            self::assertTrue(isset($header[$key]));
            self::assertSame($value, $header->current());
            self::assertSame($value, $header[$key]);
        }
    }

    public function testInvalidHeaderName(): void
    {
        $this->expectException(InvalidNameException::class);

        Header::fromValue(' ');
    }

    public function testInvalidHeaderValue(): void
    {
        $this->expectException(InvalidValueException::class);

        new Header('valid', "\x0a");
    }

    public function testUnsupportedOffsetSetException(): void
    {
        $this->expectException(UnsupportedOffsetSetException::class);

        $header    = new Header('valid', 'test');
        $header[1] = 'fire';
    }

    public function testUnsupportedOffsetUnsetException(): void
    {
        $this->expectException(UnsupportedOffsetUnsetException::class);

        $header = new Header('valid', 'test');

        unset($header[0]);
    }
}
