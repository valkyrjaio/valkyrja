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

namespace Valkyrja\Tests\Classes\Vendor\Predis;

use Predis\Client;
use Predis\Response\Status;

class ClientClass extends Client
{
    public function exists(string $key): int
    {
        return 1;
    }

    public function get(string $key): string|null
    {
        return 'test';
    }

    public function mget(array|string $keyOrKeys, string ...$keys): array
    {
        return [];
    }

    public function setex(string $key, $seconds, $value): Status
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
        $value,
        null $expireResolution = null,
        null $expireTTL = null,
        null $flag = null,
        null $flagValue = null
    ): Status|null {
        return new Status('OK');
    }

    public function del(array|string $keyOrKeys, string ...$keys): int
    {
        return 1;
    }

    public function flushdb(): mixed
    {
        return true;
    }
}
