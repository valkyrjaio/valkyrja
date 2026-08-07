<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPortException;
use Valkyrja\Http\Message\Uri\Type\Port;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function random_int;

final class PortTest extends TestCase
{
    /**
     * @return array<int, array{int}>
     */
    public static function validPortsProvider(): array
    {
        return [
            [1],
            [random_int(1, 65535)],
            [65535],
        ];
    }

    /**
     * @return array<int, array{int}>
     */
    public static function invalidPortsProvider(): array
    {
        return [
            [-1],
            [0],
            [65536],
        ];
    }

    /**
     * @param int $portNum The port to test
     */
    #[DataProvider('validPortsProvider')]
    public function testValidPorts(int $portNum): void
    {
        $port = new Port($portNum);

        self::assertSame($portNum, $port->asFlatValue());
        self::assertSame($portNum, $port->asValue());

        $port2 = Port::fromValue($portNum);

        self::assertSame($portNum, $port2->asFlatValue());
        self::assertSame($portNum, $port2->asValue());
    }

    /**
     * @param int $portNum The port to test
     */
    #[DataProvider('invalidPortsProvider')]
    public function testInvalidPorts(int $portNum): void
    {
        $this->expectException(HttpUriInvalidPortException::class);

        new Port($portNum);
    }

    public function testFromValueInvalid(): void
    {
        $this->expectException(HttpUriInvalidPortException::class);

        Port::fromValue('test');
    }
}
