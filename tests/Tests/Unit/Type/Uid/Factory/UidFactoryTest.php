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

namespace Valkyrja\Tests\Unit\Type\Uid\Factory;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Uid\Factory\UidFactory;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;

final class UidFactoryTest extends TestCase
{
    public function testIsValidReturnsTrueForWordCharacters(): void
    {
        self::assertTrue(UidFactory::isValid('abc123'));
    }

    public function testIsValidReturnsFalseForNonWordCharacters(): void
    {
        self::assertFalse(UidFactory::isValid('has space'));
        self::assertFalse(UidFactory::isValid(''));
    }

    public function testValidatePassesForValidUid(): void
    {
        $this->expectNotToPerformAssertions();

        UidFactory::validate('abc123');
    }

    public function testValidateThrowsForInvalidUid(): void
    {
        $this->expectException(InvalidUlidException::class);
        $this->expectExceptionMessage('Invalid UID has space provided.');

        UidFactory::validate('has space');
    }
}