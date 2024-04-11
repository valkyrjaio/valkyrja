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

use Valkyrja\Dispatcher\Models\Dispatch;
use Valkyrja\Event\Listener as Contract;

/**
 * Class Listener.
 *
 * @author Melech Mizrachi
 */
class Listener extends Dispatch implements Contract
{
    /**
     * The event to listen to.
     *
     * @var class-string
     */
    protected string $eventId;

    /**
     * @inheritDoc
     */
    public function getEventId(): string
    {
        return $this->eventId;
    }

    /**
     * @inheritDoc
     */
    public function setEventId(string $eventId): static
    {
        $this->eventId = $eventId;

        return $this;
    }
}
