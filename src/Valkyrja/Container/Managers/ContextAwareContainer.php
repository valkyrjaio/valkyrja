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

namespace Valkyrja\Container\Managers;

use Valkyrja\Container\Contract\ContextAwareContainer as Contract;

/**
 * Class ContextContainer.
 *
 * @author Melech Mizrachi
 */
class ContextAwareContainer extends Container implements Contract
{
    use ContextableContainer;
}
