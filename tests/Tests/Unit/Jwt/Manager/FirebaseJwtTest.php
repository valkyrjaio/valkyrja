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

namespace Valkyrja\Tests\Unit\Jwt\Manager;

use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Jwt\Manager\FirebaseJwt;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class FirebaseJwtTest extends TestCase
{
    protected FirebaseJwt $jwt;

    protected string $secretKey = 'test-secret-key-for-hs256-algorithm';

    protected function setUp(): void
    {
        $this->jwt = new FirebaseJwt(
            $this->secretKey,
            $this->secretKey,
            Algorithm::HS256
        );
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(JwtContract::class, $this->jwt);
    }

    public function testEncodeReturnsJwtString(): void
    {
        $payload = ['user_id' => 123, 'role' => 'admin'];

        $encoded = $this->jwt->encode($payload);

        // JWT format: header.payload.signature
        self::assertMatchesRegularExpression('/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/', $encoded);
    }

    public function testDecodeReturnsArray(): void
    {
        $payload = ['user_id' => 123, 'role' => 'admin'];
        $encoded = $this->jwt->encode($payload);

        $decoded = $this->jwt->decode($encoded);

        self::assertSame(123, $decoded['user_id']);
        self::assertSame('admin', $decoded['role']);
    }

    public function testEncodeAndDecodeRoundTrip(): void
    {
        $payload = [
            'sub'  => '1234567890',
            'name' => 'John Doe',
            'iat'  => 1516239022,
        ];

        $encoded = $this->jwt->encode($payload);
        $decoded = $this->jwt->decode($encoded);

        self::assertSame($payload['sub'], $decoded['sub']);
        self::assertSame($payload['name'], $decoded['name']);
        self::assertSame($payload['iat'], $decoded['iat']);
    }

    public function testEncodeWithDifferentAlgorithm(): void
    {
        $jwt = new FirebaseJwt(
            $this->secretKey,
            $this->secretKey,
            Algorithm::HS256
        );

        $payload = ['test' => 'value'];
        $encoded = $jwt->encode($payload);

        self::assertMatchesRegularExpression('/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/', $encoded);
    }

    public function testEncodeEmptyPayload(): void
    {
        $encoded = $this->jwt->encode([]);

        self::assertMatchesRegularExpression('/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/', $encoded);
    }
}
