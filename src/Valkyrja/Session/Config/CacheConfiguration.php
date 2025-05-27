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

namespace Valkyrja\Session\Config;

use Valkyrja\Session\Adapter\CacheAdapter;
use Valkyrja\Session\Constant\ConfigName;

/**
 * Class CacheConfiguration.
 *
 * @author Melech Mizrachi
 */
class CacheConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'SESSION_CACHE_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'SESSION_CACHE_DRIVER_CLASS',
        'cache'                   => 'SESSION_CACHE_CACHE',
    ];

    public function __construct(
        public string|null $cache = null
    ) {
        parent::__construct(
            adapterClass: CacheAdapter::class,
        );
    }
}
