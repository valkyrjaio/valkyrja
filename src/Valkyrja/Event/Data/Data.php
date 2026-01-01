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

namespace Valkyrja\Event\Data;

use Valkyrja\Event\Data\Contract\ListenerContract;

/**
 * Class Data.
 */
readonly class Data
{
    /**
     * The listeners.
     *
     * @param array<class-string, string[]>          $events
     * @param array<string, ListenerContract|string> $listeners
     */
    public function __construct(
        public array $events = [],
        public array $listeners = [],
    ) {
    }
}
