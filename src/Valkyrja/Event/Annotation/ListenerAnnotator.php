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

namespace Valkyrja\Event\Annotation;

use Valkyrja\Annotation\Annotator;

/**
 * Interface ListenerAnnotations.
 *
 * @author Melech Mizrachi
 */
interface ListenerAnnotator extends Annotator
{
    /**
     * Get the events.
     *
     * @param string ...$classes The classes
     *
     * @return Listener[]
     */
    public function getListeners(string ...$classes): array;
}
