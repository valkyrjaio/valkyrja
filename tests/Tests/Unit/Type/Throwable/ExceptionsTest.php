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

namespace Valkyrja\Tests\Unit\Type\Throwable;

use Throwable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;
use Valkyrja\Type\Object\Throwable\Contract\ObjectThrowable;
use Valkyrja\Type\Object\Throwable\Exception\InvalidObjectPropertyProvidedException;
use Valkyrja\Type\Object\Throwable\Exception\InvalidObjectProvidedException;
use Valkyrja\Type\Throwable\Contract\TypeThrowable;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeRuntimeException;
use Valkyrja\Type\Uid\Throwable\Contract\UidThrowable;
use Valkyrja\Type\Uid\Throwable\Exception\InvalidUidException;
use Valkyrja\Type\Ulid\Throwable\Contract\UlidThrowable;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Uuid\Throwable\Contract\UuidThrowable;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidException;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV1Exception;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV3Exception;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV4Exception;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV5Exception;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV6Exception;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV7Exception;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV8Exception;
use Valkyrja\Type\Vlid\Throwable\Contract\VlidThrowable;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidException;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV1Exception;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV2Exception;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV3Exception;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV4Exception;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, TypeThrowable::class);
        self::isA(ValkyrjaThrowable::class, TypeThrowable::class);
    }

    public function testClassThrowable(): void
    {
        self::isA(TypeThrowable::class, ObjectThrowable::class);
    }

    public function testUidThrowable(): void
    {
        self::isA(TypeThrowable::class, UidThrowable::class);
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
        self::isA(TypeThrowable::class, TypeInvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, TypeInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(TypeThrowable::class, TypeRuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, TypeRuntimeException::class);
    }

    public function testInvalidClassPropertyProvidedException(): void
    {
        self::isA(ObjectThrowable::class, InvalidObjectPropertyProvidedException::class);
        self::isA(TypeInvalidArgumentException::class, InvalidObjectPropertyProvidedException::class);
    }

    public function testInvalidClassProvidedException(): void
    {
        self::isA(ObjectThrowable::class, InvalidObjectProvidedException::class);
        self::isA(TypeInvalidArgumentException::class, InvalidObjectProvidedException::class);
    }

    public function testInvalidUidException(): void
    {
        self::isA(UidThrowable::class, InvalidUidException::class);
        self::isA(TypeInvalidArgumentException::class, InvalidUidException::class);
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
