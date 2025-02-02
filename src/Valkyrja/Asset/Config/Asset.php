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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Asset\Config as Model;
use Valkyrja\Asset\Constant\ConfigValue;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Exception\InvalidArgumentException;

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

        $host     = env(EnvKey::ASSET_DEFAULT_HOST, '');
        $path     = env(EnvKey::ASSET_DEFAULT_PATH, '/');
        $manifest = env(EnvKey::ASSET_DEFAULT_MANIFEST, '/rev-manifest.json');

        if (! is_string($host)) {
            throw new InvalidArgumentException('Host should be a string');
        }

        if (! is_string($path)) {
            throw new InvalidArgumentException('Path should be a string');
        }

        if (! is_string($manifest)) {
            throw new InvalidArgumentException('Manifest should be a string');
        }

        $this->adapters = array_merge(ConfigValue::ADAPTERS, []);
        $this->bundles  = [
            CKP::DEFAULT => [
                CKP::ADAPTER  => CKP::DEFAULT,
                CKP::HOST     => $host,
                CKP::PATH     => $path,
                CKP::MANIFEST => $manifest,
            ],
        ];
    }
}
