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

namespace Valkyrja\Jwt\Managers;

use Valkyrja\Jwt\Config\Config;
use Valkyrja\Jwt\Driver;
use Valkyrja\Jwt\Factory;
use Valkyrja\Jwt\Jwt as Contract;
use Valkyrja\Manager\Managers\Manager;

/**
 * Class Jwt.
 *
 * @author Melech Mizrachi
 *
 * @property Factory $factory
 */
class Jwt extends Manager implements Contract
{
    /**
     * JWT constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['algos'];
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
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
