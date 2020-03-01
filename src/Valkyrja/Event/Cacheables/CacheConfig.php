<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Event\Cacheables;

use Valkyrja\Config\Models\ConfigModel;

/**
 * Class CacheConfig.
 *
 * @author Melech Mizrachi
 */
class CacheConfig extends ConfigModel
{
    /**
     * The base64 encoded events.
     *
     * @var string
     */
    public string $events;
}