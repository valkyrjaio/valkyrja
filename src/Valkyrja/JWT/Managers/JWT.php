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

namespace Valkyrja\JWT\Managers;

use Valkyrja\JWT\Driver;
use Valkyrja\JWT\JWT as Contract;
use Valkyrja\JWT\Factory;
use Valkyrja\Support\Manager\Managers\Manager;

/**
 * Class JWT.
 *
 * @author Melech Mizrachi
 *
 * @property Factory $factory
 */
class JWT extends Manager implements Contract
{
    /**
     * JWT constructor.
     *
     * @param Factory $factory The factory
     * @param array   $config  The config
     */
    public function __construct(Factory $factory, array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['algos'];
    }

    /**
     * @inheritDoc
     */
    public function use(string $name = null): Driver
    {
        return parent::use($name);
    }

    /**
     * @inheritDoc
     */
    public function encode(array $payload): string
    {
        return $this->use()->encode($payload);
    }

    /**
     * @inheritDoc
     */
    public function decode(string $jwt): array
    {
        return $this->use()->decode($jwt);
    }
}
