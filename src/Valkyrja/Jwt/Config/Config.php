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

namespace Valkyrja\Jwt\Config;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\Constant\EnvKey;
use Valkyrja\Manager\Config as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        CKP::DEFAULT => EnvKey::JWT_DEFAULT,
        CKP::ADAPTER => EnvKey::JWT_ADAPTER,
        CKP::DRIVER  => EnvKey::JWT_DRIVER,
        CKP::ALGOS   => EnvKey::JWT_ALGOS,
    ];

    /**
     * @inheritDoc
     */
    public string $default;

    /**
     * @inheritDoc
     */
    public string $adapter;

    /**
     * @inheritDoc
     */
    public string $driver;

    /**
     * The algorithms.
     *
     * @var array[]
     */
    public array $algos;
}
