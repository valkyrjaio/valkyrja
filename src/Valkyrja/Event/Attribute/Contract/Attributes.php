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

namespace Valkyrja\Event\Attribute\Contract;

use Valkyrja\Event\Model\Contract\Listener;

/**
 * Interface Attributes.
 *
 * @author Melech Mizrachi
 */
interface Attributes
{
    /**
     * Get the listeners.
     *
     * @param class-string ...$classes The classes
     *
     * @return Listener[]
     */
    public function getListeners(string ...$classes): array;
}
