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

use Valkyrja\Asset\Constant\ConfigName;

/**
 * Class DefaultBundle.
 *
 * @author Melech Mizrachi
 */
class DefaultBundle extends Bundle
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'ASSET_DFAULT_ADAPTER_CLASS',
        ConfigName::HOST          => 'ASSET_DFAULT_HOST',
        ConfigName::PATH          => 'ASSET_DFAULT_PATH',
        ConfigName::MANIFEST      => 'ASSET_DFAULT_MANIFEST',
    ];
}
