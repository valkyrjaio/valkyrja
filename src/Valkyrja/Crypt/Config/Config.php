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

namespace Valkyrja\Crypt\Config;

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
        CKP::DEFAULT => EnvKey::CRYPT_DEFAULT,
        CKP::ADAPTER => EnvKey::CRYPT_ADAPTER,
        CKP::DRIVER  => EnvKey::CRYPT_DRIVER,
        CKP::CRYPTS  => EnvKey::CRYPT_CRYPTS,
    ];

    /**
     * The default crypt.
     *
     * @var string
     */
    public string $default;

    /**
     * The adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The driver.
     *
     * @var string
     */
    public string $driver;

    /**
     * The config.
     *
     * @var array
     */
    public array $crypts;
}
