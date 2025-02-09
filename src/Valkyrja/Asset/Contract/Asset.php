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

namespace Valkyrja\Asset\Contract;

use Valkyrja\Asset\Adapter\Contract\Adapter;

/**
 * Interface Asset.
 *
 * @author Melech Mizrachi
 */
interface Asset
{
    /**
     * Get a bundle.
     *
     * @param string      $bundle  The bundle name
     * @param string|null $adapter [optional] The adapter to use
     *
     * @return Adapter
     */
    public function getBundle(string $bundle, ?string $adapter = null): Adapter;
}
