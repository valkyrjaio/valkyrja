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

use Override;
use Valkyrja\Dispatch\Data\ClassDispatch as DefaultDispatch;
use Valkyrja\Dispatch\Data\Contract\ClassDispatchContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Event\Data\Contract\ListenerContract as Contract;

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
        protected ClassDispatchContract|MethodDispatchContract $dispatch = new DefaultDispatch(self::class),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventId(): string
    {
        return $this->eventId;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withEventId(string $eventId): static
    {
        $new = clone $this;

        $new->eventId = $eventId;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDispatch(): ClassDispatchContract|MethodDispatchContract
    {
        return $this->dispatch;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDispatch(ClassDispatchContract|MethodDispatchContract $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }
}
