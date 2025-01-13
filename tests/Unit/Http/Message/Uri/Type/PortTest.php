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

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Type\Port;
use Valkyrja\Tests\Unit\TestCase;

use function random_int;

class PortTest extends TestCase
{
    public static function validPortsProvider(): array
    {
        // $validPorts = range(1, 65535);

        // return array_map(static fn (int $port): array => [$port], $validPorts);
        return [
            [1],
            [random_int(1, 65535)],
            [65535],
        ];
    }

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
     *
     * @return void
     */
    #[DataProvider('validPortsProvider')]
    public function testValidPorts(int $portNum): void
    {
        $port = new Port($portNum);

        self::assertSame($portNum, $port->asFlatValue());
        self::assertSame($portNum, $port->asValue());
    }

    public function testNullIsValidPorts(): void
    {
        $port = new Port(null);

        self::assertNull($port->asFlatValue());
        self::assertNull($port->asValue());
    }

    /**
     * @param int $portNum The port to test
     *
     * @return void
     */
    #[DataProvider('invalidPortsProvider')]
    public function testInvalidPorts(int $portNum): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Port($portNum);
    }
}
