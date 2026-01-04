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

namespace Valkyrja\Jwt\Manager\Contract;

interface JwtContract
{
    /**
     * Encode a payload array into a JWT string.
     *
     * @param array<array-key, mixed> $payload The payload
     */
    public function encode(array $payload): string;

    /**
     * Decode a JWT string into a payload array.
     *
     * @param string $jwt The JWT string
     *
     * @return array<array-key, mixed>
     */
    public function decode(string $jwt): array;
}
