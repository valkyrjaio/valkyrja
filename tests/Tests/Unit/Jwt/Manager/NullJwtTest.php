<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Jwt\Manager;

use JsonException;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Jwt\Manager\NullJwt;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullJwtTest extends TestCase
{
    protected NullJwt $jwt;

    protected function setUp(): void
    {
        $this->jwt = new NullJwt();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(JwtContract::class, $this->jwt);
    }

    /**
     * @throws JsonException
     */
    public function testEncodeReturnsJsonString(): void
    {
        $payload = ['user_id' => 123, 'role' => 'admin'];

        $encoded = $this->jwt->encode($payload);

        self::assertJson($encoded);
        self::assertSame('{"user_id":123,"role":"admin"}', $encoded);
    }

    /**
     * @throws JsonException
     */
    public function testDecodeReturnsArray(): void
    {
        $jwt = '{"user_id":123,"role":"admin"}';

        $decoded = $this->jwt->decode($jwt);

        self::assertSame(['user_id' => 123, 'role' => 'admin'], $decoded);
    }

    /**
     * @throws JsonException
     */
    public function testEncodeAndDecodeRoundTrip(): void
    {
        $payload = [
            'sub'  => '1234567890',
            'name' => 'John Doe',
            'iat'  => 1516239022,
        ];

        $encoded = $this->jwt->encode($payload);
        $decoded = $this->jwt->decode($encoded);

        self::assertSame($payload, $decoded);
    }

    /**
     * @throws JsonException
     */
    public function testEncodeEmptyPayload(): void
    {
        $encoded = $this->jwt->encode([]);

        self::assertSame('[]', $encoded);
    }

    /**
     * @throws JsonException
     */
    public function testDecodeEmptyArray(): void
    {
        $decoded = $this->jwt->decode('[]');

        self::assertSame([], $decoded);
    }
}
