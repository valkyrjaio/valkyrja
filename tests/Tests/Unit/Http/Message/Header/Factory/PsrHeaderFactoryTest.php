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

use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Factory\PsrHeaderFactory;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Value\Value;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class PsrHeaderFactoryTest extends TestCase
{
    public function testFromPsr(): void
    {
        $psrHeaders = [
            'Content-Type'  => ['application/json'],
            'Accept'        => ['text/html', 'application/json'],
            'Cache-Control' => ['no-cache', 'no-store', 'must-revalidate'],
        ];

        $headers = PsrHeaderFactory::fromPsr($psrHeaders);

        self::assertCount(3, $headers);
        self::assertContainsOnlyInstancesOf(HeaderContract::class, $headers);

        self::assertSame('Content-Type', $headers[0]->getName());
        self::assertSame('application/json', $headers[0]->getValuesAsString());

        self::assertSame('Accept', $headers[1]->getName());
        self::assertCount(2, $headers[1]->getValues());
        self::assertSame('text/html, application/json', $headers[1]->getValuesAsString());

        self::assertSame('Cache-Control', $headers[2]->getName());
        self::assertCount(3, $headers[2]->getValues());
        self::assertSame('no-cache, no-store, must-revalidate', $headers[2]->getValuesAsString());
    }

    public function testFromPsrWithEmptyArray(): void
    {
        $headers = PsrHeaderFactory::fromPsr([]);

        self::assertCount(0, $headers);
        self::assertSame([], $headers);
    }

    public function testFromPsrWithSingleValueHeaders(): void
    {
        $psrHeaders = [
            'Host'         => ['example.com'],
            'Content-Type' => ['text/plain'],
        ];

        $headers = PsrHeaderFactory::fromPsr($psrHeaders);

        self::assertCount(2, $headers);

        self::assertSame('Host', $headers[0]->getName());
        self::assertCount(1, $headers[0]->getValues());
        self::assertSame('example.com', $headers[0]->getValuesAsString());

        self::assertSame('Content-Type', $headers[1]->getName());
        self::assertCount(1, $headers[1]->getValues());
        self::assertSame('text/plain', $headers[1]->getValuesAsString());
    }

    public function testToPsr(): void
    {
        $headers = new HeaderCollection(
            new Header('Content-Type', 'application/json'),
            new Header('Accept', 'text/html', 'application/json'),
            new Header('Cache-Control', 'no-cache', 'no-store'),
        );

        $psrHeaders = PsrHeaderFactory::toPsr($headers);

        self::assertCount(3, $psrHeaders);

        self::assertArrayHasKey('Content-Type', $psrHeaders);
        self::assertSame(['application/json'], $psrHeaders['Content-Type']);

        self::assertArrayHasKey('Accept', $psrHeaders);
        self::assertSame(['text/html', 'application/json'], $psrHeaders['Accept']);

        self::assertArrayHasKey('Cache-Control', $psrHeaders);
        self::assertSame(['no-cache', 'no-store'], $psrHeaders['Cache-Control']);
    }

    public function testToPsrWithEmptyArray(): void
    {
        $psrHeaders = PsrHeaderFactory::toPsr(new HeaderCollection());

        self::assertCount(0, $psrHeaders);
        self::assertSame([], $psrHeaders);
    }

    public function testToPsrWithValueContracts(): void
    {
        $value1 = new Value('application/json');
        $value2 = new Value('text/html');

        $headers = new HeaderCollection(
            new Header('Content-Type', $value1),
            new Header('Accept', $value2, 'application/xml'),
        );

        $psrHeaders = PsrHeaderFactory::toPsr($headers);

        self::assertArrayHasKey('Content-Type', $psrHeaders);
        self::assertSame(['application/json'], $psrHeaders['Content-Type']);

        self::assertArrayHasKey('Accept', $psrHeaders);
        self::assertSame(['text/html', 'application/xml'], $psrHeaders['Accept']);
    }

    public function testToPsrValues(): void
    {
        $header = new Header('Accept', 'text/html', 'application/json', 'application/xml');

        $values = PsrHeaderFactory::toPsrValues($header);

        self::assertCount(3, $values);
        self::assertSame(['text/html', 'application/json', 'application/xml'], $values);
    }

    public function testToPsrValuesWithSingleValue(): void
    {
        $header = new Header('Content-Type', 'application/json');

        $values = PsrHeaderFactory::toPsrValues($header);

        self::assertCount(1, $values);
        self::assertSame(['application/json'], $values);
    }

    public function testToPsrValuesWithValueContracts(): void
    {
        $value1 = new Value('text/html');
        $value2 = new Value('charset=utf-8');

        $header = new Header('Content-Type', $value1, $value2);

        $values = PsrHeaderFactory::toPsrValues($header);

        self::assertCount(2, $values);
        self::assertSame(['text/html', 'charset=utf-8'], $values);
    }

    public function testToPsrValuesWithMixedValues(): void
    {
        $valueContract = new Value('application/json');

        $header = new Header('Accept', 'text/html', $valueContract, 'application/xml');

        $values = PsrHeaderFactory::toPsrValues($header);

        self::assertCount(3, $values);
        self::assertSame(['text/html', 'application/json', 'application/xml'], $values);
    }

    public function testToPsrValuesWithValueContractHavingComponents(): void
    {
        $value = Value::fromValue('text/html;charset=utf-8');

        $header = new Header('Content-Type', $value);

        $values = PsrHeaderFactory::toPsrValues($header);

        self::assertCount(1, $values);
        self::assertSame(['text/html; charset=utf-8'], $values);
    }

    public function testFromPsrAndToPsrRoundTrip(): void
    {
        $originalPsrHeaders = [
            'Content-Type'  => ['application/json'],
            'Accept'        => ['text/html', 'application/json'],
            'Cache-Control' => ['no-cache', 'no-store'],
        ];

        $valkyrjaHeaders     = PsrHeaderFactory::fromPsr($originalPsrHeaders);
        $roundTripPsrHeaders = PsrHeaderFactory::toPsr(new HeaderCollection(...$valkyrjaHeaders));

        self::assertSame($originalPsrHeaders, $roundTripPsrHeaders);
    }

    public function testToPsrValuesWithEmptyHeader(): void
    {
        $header = new Header('X-Empty');

        $values = PsrHeaderFactory::toPsrValues($header);

        self::assertCount(0, $values);
        self::assertSame([], $values);
    }
}
