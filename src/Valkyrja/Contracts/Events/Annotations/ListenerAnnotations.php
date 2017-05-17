<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Events\Annotations;

use Valkyrja\Contracts\Annotations\Annotations;

/**
 * Interface ListenerAnnotations.
 *
 * @author Melech Mizrachi
 */
interface ListenerAnnotations extends Annotations
{
    /**
     * Get the events.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Events\Listener[]
     */
    public function getListeners(string ...$classes): array;
}
