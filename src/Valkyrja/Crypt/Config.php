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

namespace Valkyrja\Crypt;

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
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
        CKP::DEFAULT => EnvKey::CRYPT_DEFAULT,
        CKP::ADAPTER => EnvKey::CRYPT_ADAPTER,
        CKP::DRIVER  => EnvKey::CRYPT_DRIVER,
        CKP::CRYPTS  => EnvKey::CRYPT_CRYPTS,
    ];

    /**
     * The config.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $crypts;
}
