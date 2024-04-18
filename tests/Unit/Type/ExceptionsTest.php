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

namespace Valkyrja\Tests\Unit\Type;

use Throwable as PHPThrowable;
use Valkyrja\Exception\InvalidArgumentException as ValkyrjaInvalidArgumentException;
use Valkyrja\Exception\RuntimeException as ValkyrjaRuntimeException;
use Valkyrja\Exception\Throwable as ValkyrjaThrowable;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Exceptions\ClassThrowable;
use Valkyrja\Type\Exceptions\InvalidArgumentException;
use Valkyrja\Type\Exceptions\InvalidClassPropertyProvidedException;
use Valkyrja\Type\Exceptions\InvalidClassProvidedException;
use Valkyrja\Type\Exceptions\InvalidUidException;
use Valkyrja\Type\Exceptions\InvalidUlidException;
use Valkyrja\Type\Exceptions\InvalidUuidException;
use Valkyrja\Type\Exceptions\InvalidUuidV1Exception;
use Valkyrja\Type\Exceptions\InvalidUuidV3Exception;
use Valkyrja\Type\Exceptions\InvalidUuidV4Exception;
use Valkyrja\Type\Exceptions\InvalidUuidV5Exception;
use Valkyrja\Type\Exceptions\InvalidUuidV6Exception;
use Valkyrja\Type\Exceptions\InvalidUuidV7Exception;
use Valkyrja\Type\Exceptions\InvalidUuidV8Exception;
use Valkyrja\Type\Exceptions\InvalidVlidException;
use Valkyrja\Type\Exceptions\InvalidVlidV1Exception;
use Valkyrja\Type\Exceptions\InvalidVlidV2Exception;
use Valkyrja\Type\Exceptions\InvalidVlidV3Exception;
use Valkyrja\Type\Exceptions\InvalidVlidV4Exception;
use Valkyrja\Type\Exceptions\RuntimeException;
use Valkyrja\Type\Exceptions\Throwable;
use Valkyrja\Type\Exceptions\UidThrowable;
use Valkyrja\Type\Exceptions\UlidThrowable;
use Valkyrja\Type\Exceptions\UuidThrowable;
use Valkyrja\Type\Exceptions\VlidThrowable;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(ValkyrjaThrowable::class, Throwable::class);
    }

    public function testClassThrowable(): void
    {
        self::isA(Throwable::class, ClassThrowable::class);
    }

    public function testUidThrowable(): void
    {
        self::isA(Throwable::class, UidThrowable::class);
    }

    public function testUlidThrowable(): void
    {
        self::isA(UidThrowable::class, UlidThrowable::class);
    }

    public function testUuidThrowable(): void
    {
        self::isA(UidThrowable::class, UuidThrowable::class);
    }

    public function testVlidThrowable(): void
    {
        self::isA(UidThrowable::class, VlidThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, RuntimeException::class);
    }

    public function testInvalidClassPropertyProvidedException(): void
    {
        self::isA(ClassThrowable::class, InvalidClassPropertyProvidedException::class);
        self::isA(InvalidArgumentException::class, InvalidClassPropertyProvidedException::class);
    }

    public function testInvalidClassProvidedException(): void
    {
        self::isA(ClassThrowable::class, InvalidClassProvidedException::class);
        self::isA(InvalidArgumentException::class, InvalidClassProvidedException::class);
    }

    public function testInvalidUidException(): void
    {
        self::isA(UidThrowable::class, InvalidUidException::class);
        self::isA(InvalidArgumentException::class, InvalidUidException::class);
    }

    public function testInvalidUlidException(): void
    {
        self::isA(UlidThrowable::class, InvalidUlidException::class);
        self::isA(InvalidUidException::class, InvalidUlidException::class);
    }

    public function testInvalidUuidException(): void
    {
        self::isA(UuidThrowable::class, InvalidUuidException::class);
        self::isA(InvalidUidException::class, InvalidUuidException::class);
    }

    public function testInvalidUuidV1Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV1Exception::class);
    }

    public function testInvalidUuidV3Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV3Exception::class);
    }

    public function testInvalidUuidV4Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV4Exception::class);
    }

    public function testInvalidUuidV5Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV5Exception::class);
    }

    public function testInvalidUuidV6Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV6Exception::class);
    }

    public function testInvalidUuidV7Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV7Exception::class);
    }

    public function testInvalidUuidV8Exception(): void
    {
        self::isA(InvalidUuidException::class, InvalidUuidV8Exception::class);
    }

    public function testInvalidVlidException(): void
    {
        self::isA(VlidThrowable::class, InvalidVlidException::class);
        self::isA(InvalidUidException::class, InvalidVlidException::class);
    }

    public function testInvalidVlidV1Exception(): void
    {
        self::isA(InvalidVlidException::class, InvalidVlidV1Exception::class);
    }

    public function testInvalidVlidV2Exception(): void
    {
        self::isA(InvalidVlidException::class, InvalidVlidV2Exception::class);
    }

    public function testInvalidVlidV3Exception(): void
    {
        self::isA(InvalidVlidException::class, InvalidVlidV3Exception::class);
    }

    public function testInvalidVlidV4Exception(): void
    {
        self::isA(InvalidVlidException::class, InvalidVlidV4Exception::class);
    }
}
