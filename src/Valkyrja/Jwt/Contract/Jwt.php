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

namespace Valkyrja\Jwt\Contract;

use Valkyrja\Jwt\Driver\Contract\Driver;

/**
 * Interface Jwt.
 *
 * @author Melech Mizrachi
 */
interface Jwt
{
    /**
     * Use a specific configuration.
     */
    public function use(string|null $name = null): Driver;

    /**
     * Encode a payload array into a JWT string.
     *
     * @param array<array-key, mixed> $payload The payload
     *
     * @return string
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
