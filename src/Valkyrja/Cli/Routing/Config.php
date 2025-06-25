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

namespace Valkyrja\Cli\Routing;

use Valkyrja\Cli\Routing\Config\Cache;
use Valkyrja\Cli\Routing\Constant\ConfigName;
use Valkyrja\Cli\Routing\Constant\EnvName;
use Valkyrja\Config\Config as ParentConfig;

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
        ConfigName::CONTROLLERS => EnvName::CONTROLLERS,
    ];

    /**
     * @param class-string[] $controllers The attributed controller classes
     */
    public function __construct(
        public array $controllers = [],
        public Cache|null $cache = null,
    ) {
    }
}
