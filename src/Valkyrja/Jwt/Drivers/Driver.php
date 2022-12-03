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

namespace Valkyrja\Jwt\Drivers;

use Valkyrja\Jwt\Adapter;
use Valkyrja\Jwt\Driver as Contract;
use Valkyrja\Manager\Drivers\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @inheritDoc
     */
    public function encode(array $payload): string
    {
        return $this->adapter->encode($payload);
    }

    /**
     * @inheritDoc
     */
    public function decode(string $jwt): array
    {
        return $this->adapter->decode($jwt);
    }
}
