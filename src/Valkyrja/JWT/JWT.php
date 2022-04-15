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

namespace Valkyrja\JWT;

/**
 * Interface JWT.
 *
 * @author Melech Mizrachi
 */
interface JWT
{
    /**
     * Use an algorithm.
     *
     * @param string|null $algo [optional] The algorithm to use
     *
     * @return Driver
     */
    public function useAlgo(string $algo = null): Driver;

    /**
     * Encode a payload array into a JWT string.
     *
     * @param array $payload The payload
     *
     * @return string
     */
    public function encode(array $payload): string;

    /**
     * Decode a JWT string into a payload array.
     *
     * @param string $jwt The JWT string
     *
     * @return array
     */
    public function decode(string $jwt): array;
}
