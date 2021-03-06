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

namespace Valkyrja\Event\Models;

/**
 * Trait Listenable.
 *
 * @author Melech Mizrachi
 */
trait Listenable
{
    /**
     * The event to listen to.
     *
     * @var string|null
     */
    public ?string $event = null;

    /**
     * Get the event.
     *
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * Set the event.
     *
     * @param string|null $event The event
     *
     * @return static
     */
    public function setEvent(string $event = null): self
    {
        $this->event = $event;

        return $this;
    }
}
