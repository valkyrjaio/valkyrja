<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Annotations;

use Valkyrja\Annotations\Annotatable;
use Valkyrja\Contracts\Annotations\Annotation;

/**
 * Class Event.
 *
 * @author Melech Mizrachi
 */
class Listener implements Annotation
{
    use Annotatable;

    /**
     * The event to listen to.
     *
     * @var string
     */
    protected $event;

    /**
     * Get the event.
     *
     * @return string
     */
    public function getEvent():? string
    {
        return $this->event;
    }

    /**
     * Set the event.
     *
     * @param string $event The event
     *
     * @return void
     */
    public function setEvent(string $event = null): void
    {
        $this->event = $event;
    }
}
