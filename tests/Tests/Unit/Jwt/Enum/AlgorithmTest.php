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

namespace Valkyrja\Tests\Unit\Jwt\Enum;

use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class AlgorithmTest extends TestCase
{
    public function testHmacAlgorithms(): void
    {
        self::assertSame('HS256', Algorithm::HS256->name);
        self::assertSame('HS384', Algorithm::HS384->name);
        self::assertSame('HS512', Algorithm::HS512->name);
    }

    public function testRsaPssAlgorithms(): void
    {
        self::assertSame('PS256', Algorithm::PS256->name);
        self::assertSame('PS384', Algorithm::PS384->name);
        self::assertSame('PS512', Algorithm::PS512->name);
    }

    public function testRsaAlgorithms(): void
    {
        self::assertSame('RS256', Algorithm::RS256->name);
        self::assertSame('RS384', Algorithm::RS384->name);
        self::assertSame('RS512', Algorithm::RS512->name);
    }

    public function testEcdsaAlgorithms(): void
    {
        self::assertSame('ES256', Algorithm::ES256->name);
        self::assertSame('ES256K', Algorithm::ES256K->name);
        self::assertSame('ES384', Algorithm::ES384->name);
        self::assertSame('ES512', Algorithm::ES512->name);
    }

    public function testEdDsaAlgorithm(): void
    {
        self::assertSame('EdDSA', Algorithm::EdDSA->name);
    }

    public function testCasesReturnsAllAlgorithms(): void
    {
        $cases = Algorithm::cases();

        self::assertCount(14, $cases);
        self::assertContains(Algorithm::HS256, $cases);
        self::assertContains(Algorithm::RS256, $cases);
        self::assertContains(Algorithm::ES256, $cases);
        self::assertContains(Algorithm::EdDSA, $cases);
    }
}
