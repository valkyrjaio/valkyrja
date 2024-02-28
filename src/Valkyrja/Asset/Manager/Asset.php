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

namespace Valkyrja\Asset\Managers;

use Valkyrja\Asset\Adapter;
use Valkyrja\Asset\Asset as Contract;

/**
 * Class Asset.
 *
 * @author Melech Mizrachi
 */
abstract class Asset implements Contract
{
    /**
     * @inheritDoc
     */
    abstract public function getBundle(string $bundle, string|null $adapter = null): Adapter;
}
