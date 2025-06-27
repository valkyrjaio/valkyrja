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

namespace Valkyrja\Http\Routing;

use Valkyrja\Http\Routing\Config\Cache;
use Valkyrja\Http\Routing\Constant\ConfigName;
use Valkyrja\Http\Routing\Constant\EnvName;
use Valkyrja\Support\Config as ParentConfig;

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
     * @param class-string[] $controllers A list of attributed controller classes
     */
    public function __construct(
        public array $controllers = [],
        public Cache|null $cache = null,
    ) {
    }
}
