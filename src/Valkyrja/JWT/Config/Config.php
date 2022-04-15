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

namespace Valkyrja\JWT\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT => EnvKey::JWT_DEFAULT,
        CKP::ADAPTER => EnvKey::JWT_ADAPTER,
        CKP::DRIVER  => EnvKey::JWT_DRIVER,
        CKP::ALGOS   => EnvKey::JWT_ALGOS,
    ];

    /**
     * The default algo.
     *
     * @var string
     */
    public string $default;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The default driver.
     *
     * @var string
     */
    public string $driver;

    /**
     * The algorithms.
     *
     * @var array[]
     */
    public array $algos;
}
