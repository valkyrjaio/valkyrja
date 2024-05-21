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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Crypt\Config\Config as Model;
use Valkyrja\Crypt\Constants\ConfigValue;

use function Valkyrja\env;

/**
 * Class Crypt.
 */
class Crypt extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->crypts = [
            CKP::SODIUM => [
                CKP::ADAPTER  => env(EnvKey::CRYPT_DEFAULT_ADAPTER),
                CKP::DRIVER   => env(EnvKey::CRYPT_DEFAULT_DRIVER),
                CKP::KEY      => env(EnvKey::CRYPT_KEY, env(EnvKey::APP_KEY)),
                CKP::KEY_PATH => env(EnvKey::CRYPT_KEY_PATH),
            ],
        ];
    }
}
