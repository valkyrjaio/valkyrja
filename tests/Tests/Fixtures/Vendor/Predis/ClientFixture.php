<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Vendor\Predis;

use Predis\Client;
use Predis\Response\Status;

class ClientFixture extends Client
{
    public function exists(string $key): int
    {
        return 1;
    }

    public function get(string $key): string|null
    {
        return 'test';
    }

    /**
     * @param string[]|string $keyOrKeys
     *
     * @return array<int, string|null>
     */
    public function mget(array|string $keyOrKeys, string ...$keys): array
    {
        return [];
    }

    public function setex(string $key, int $seconds, mixed $value): Status
    {
        return new Status('OK');
    }

    public function incrby(string $key, int $increment): int
    {
        return 1;
    }

    public function decrby(string $key, int $decrement): int
    {
        return 1;
    }

    public function set(
        string $key,
        mixed $value,
        null $expireResolution = null,
        null $expireTTL = null,
        null $flag = null,
        null $flagValue = null
    ): Status|null {
        return new Status('OK');
    }

    /**
     * @param string[]|string $keyOrKeys
     */
    public function del(array|string $keyOrKeys, string ...$keys): int
    {
        return 1;
    }

    public function flushdb(): mixed
    {
        return true;
    }
}
