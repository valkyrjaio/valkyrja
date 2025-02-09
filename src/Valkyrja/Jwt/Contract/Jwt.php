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

use Valkyrja\Jwt\Adapter\Contract\Adapter;
use Valkyrja\Jwt\Driver\Contract\Driver;
use Valkyrja\Jwt\Factory\Contract\Factory;
use Valkyrja\Manager\Contract\Manager;

/**
 * Interface Jwt.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Adapter, Driver, Factory>
 */
interface Jwt extends Manager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(?string $name = null): Driver;

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
