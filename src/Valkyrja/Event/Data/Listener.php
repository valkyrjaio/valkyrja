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

use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch as DefaultDispatch;
use Valkyrja\Event\Data\Contract\Listener as Contract;

/**
 * Class Listener.
 *
 * @author Melech Mizrachi
 */
class Listener implements Contract
{
    /**
     * @param class-string     $eventId The event class name
     * @param non-empty-string $name    A unique name for this listener
     */
    public function __construct(
        protected string $eventId,
        protected string $name,
        protected MethodDispatch $dispatch = new DefaultDispatch(self::class, '__construct'),
    ) {
    }

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
    public function withEventId(string $eventId): static
    {
        $new = clone $this;

        $new->eventId = $eventId;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDispatch(): ClassDispatch|MethodDispatch
    {
        return $this->dispatch;
    }

    /**
     * @inheritDoc
     */
    public function withDispatch(ClassDispatch|MethodDispatch $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }
}
