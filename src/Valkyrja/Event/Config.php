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

namespace Valkyrja\Event;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Event\Config\Cache;
use Valkyrja\Event\Constant\ConfigName;
use Valkyrja\Event\Constant\EnvName;

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
        ConfigName::LISTENERS => EnvName::LISTENERS,
    ];

    /**
     * @param class-string[] $listeners
     */
    public function __construct(
        public array $listeners = [],
        public Cache|null $cache = null,
    ) {
    }
}
