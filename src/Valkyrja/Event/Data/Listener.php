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
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Data\Contract\ListenerContract;

class Listener implements ListenerContract
{
    /** @var callable(ContainerContract, array<string, mixed>):mixed */
    protected $handler;

    /**
     * @param class-string                                            $eventId The event class name
     * @param non-empty-string                                        $name    A unique name for this listener
     * @param callable(ContainerContract, array<string, mixed>):mixed $handler The handler
     */
    public function __construct(
        protected string $eventId,
        protected string $name,
        callable $handler,
    ) {
        $this->handler = $handler;
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
    public function getHandler(): callable
    {
        return $this->handler;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHandler(callable $handler): static
    {
        $new = clone $this;

        $new->handler = $handler;

        return $new;
    }
}
