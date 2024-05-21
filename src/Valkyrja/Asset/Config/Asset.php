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

namespace Valkyrja\Asset\Config;

use Valkyrja\Asset\Config\Config as Model;
use Valkyrja\Asset\Constants\ConfigValue;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\Constant\EnvKey;

use function Valkyrja\env;

/**
 * Class Asset.
 */
class Asset extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->adapters = array_merge(ConfigValue::ADAPTERS, []);
        $this->bundles  = [
            CKP::DEFAULT => [
                CKP::ADAPTER  => CKP::DEFAULT,
                CKP::HOST     => env(EnvKey::ASSET_DEFAULT_HOST, ''),
                CKP::PATH     => env(EnvKey::ASSET_DEFAULT_PATH, '/'),
                CKP::MANIFEST => env(EnvKey::ASSET_DEFAULT_MANIFEST, '/rev-manifest.json'),
            ],
        ];
    }
}
