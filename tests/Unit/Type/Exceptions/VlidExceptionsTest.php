<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Type\Exceptions;

use InvalidArgumentException;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Exceptions\ClassThrowable;
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
use Valkyrja\Type\Exceptions\Throwable;
use Valkyrja\Type\Exceptions\UidThrowable;
use Valkyrja\Type\Exceptions\UlidThrowable;
use Valkyrja\Type\Exceptions\UuidThrowable;
use Valkyrja\Type\Exceptions\VlidThrowable;

class VlidExceptionsTest extends TestCase
{
    public function testTypeThrowable(): void
    {
        $this->isA(\Throwable::class, Throwable::class);
        $this->isA(\Valkyrja\Exception\Throwable::class, Throwable::class);
    }

    public function testClassExceptions(): void
    {
        $this->isA(Throwable::class, ClassThrowable::class);
        $this->isA(Throwable::class, ClassThrowable::class);

        $this->isA(ClassThrowable::class, InvalidClassProvidedException::class);
        $this->isA(InvalidArgumentException::class, InvalidClassProvidedException::class);

        $this->isA(ClassThrowable::class, InvalidClassPropertyProvidedException::class);
        $this->isA(InvalidArgumentException::class, InvalidClassPropertyProvidedException::class);
    }

    public function testUidExceptions(): void
    {
        $this->isA(Throwable::class, UidThrowable::class);
        $this->isA(Throwable::class, UidThrowable::class);

        $this->isA(UidThrowable::class, InvalidUidException::class);
        $this->isA(InvalidArgumentException::class, InvalidUidException::class);
    }

    public function testUlidExceptions(): void
    {
        $this->isA(UidThrowable::class, UlidThrowable::class);

        $this->isA(UlidThrowable::class, InvalidUlidException::class);
        $this->isA(InvalidUidException::class, InvalidUlidException::class);
    }

    public function testUuidExceptions(): void
    {
        $this->isA(UidThrowable::class, UuidThrowable::class);

        $this->isA(UuidThrowable::class, InvalidUuidException::class);
        $this->isA(InvalidUidException::class, InvalidUuidException::class);

        $this->isA(InvalidUuidException::class, InvalidUuidV1Exception::class);
        $this->isA(InvalidUuidException::class, InvalidUuidV3Exception::class);
        $this->isA(InvalidUuidException::class, InvalidUuidV4Exception::class);
        $this->isA(InvalidUuidException::class, InvalidUuidV5Exception::class);
        $this->isA(InvalidUuidException::class, InvalidUuidV6Exception::class);
        $this->isA(InvalidUuidException::class, InvalidUuidV7Exception::class);
        $this->isA(InvalidUuidException::class, InvalidUuidV8Exception::class);
    }

    public function testVlidExceptions(): void
    {
        $this->isA(UidThrowable::class, VlidThrowable::class);

        $this->isA(VlidThrowable::class, InvalidVlidException::class);
        $this->isA(InvalidUidException::class, InvalidVlidException::class);

        $this->isA(InvalidVlidException::class, InvalidVlidV1Exception::class);
        $this->isA(InvalidVlidException::class, InvalidVlidV2Exception::class);
        $this->isA(InvalidVlidException::class, InvalidVlidV3Exception::class);
        $this->isA(InvalidVlidException::class, InvalidVlidV4Exception::class);
    }
}
