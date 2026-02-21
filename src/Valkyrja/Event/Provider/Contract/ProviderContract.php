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

namespace Valkyrja\Event\Provider\Contract;

use Valkyrja\Event\Data\Contract\ListenerContract;

interface ProviderContract
{
    /**
     * Get a list of attributed listener classes.
     *
     * @return class-string[]
     */
    public static function getListenerClasses(): array;

    /**
     * Get a list of listeners.
     *
     * @return ListenerContract[]
     */
    public static function getListeners(): array;
}
