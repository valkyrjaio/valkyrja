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

namespace Valkyrja\Container;

use Valkyrja\Support\Cacheable;

/**
 * Interface CacheableContainer.
 *
 * @author Melech Mizrachi
 */
interface CacheableContainer extends Cacheable, Container
{
}
