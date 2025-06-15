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

use Valkyrja\Asset\Adapter\Contract\Adapter;
use Valkyrja\Asset\Adapter\DefaultAdapter;
use Valkyrja\Config\Config as ParentConfig;

/**
 * Abstract Class Bundle.
 *
 * @author Melech Mizrachi
 */
abstract class Bundle extends ParentConfig
{
    /**
     * @param class-string<Adapter> $adapterClass The adapter class
     */
    public function __construct(
        public string $adapterClass = DefaultAdapter::class,
        public string $host = '',
        public string $path = '/',
        public string $manifest = '/rev-manifest.json',
    ) {
    }
}
