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

namespace Valkyrja\Asset;

use Valkyrja\Asset\Config\Bundles;
use Valkyrja\Asset\Config\DefaultBundle;
use Valkyrja\Asset\Constant\ConfigName;
use Valkyrja\Asset\Constant\EnvName;
use Valkyrja\Config\Config as ParentConfig;

use function array_key_first;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::DEFAULT_BUNDLE => EnvName::DEFAULT_BUNDLE,
    ];

    public function __construct(
        public string $defaultBundle = '',
        public Bundles|null $bundles = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->bundles === null) {
            $this->bundles = new Bundles(
                default: DefaultBundle::fromEnv($env),
            );
        }

        if ($this->defaultBundle === '') {
            $this->defaultBundle = (string) array_key_first((array) $this->bundles);
        }
    }
}
