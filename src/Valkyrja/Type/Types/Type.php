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

namespace Valkyrja\Type\Types;

use Closure;
use Valkyrja\Type\Type as Contract;

/**
 * Class Type.
 *
 * @author Melech Mizrachi
 */
abstract class Type implements Contract
{
    public function __construct(
        protected mixed $subject,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function get(): mixed
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function modify(Closure $closure): static
    {
        $new = clone $this;

        $new->subject = $closure($new->subject);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [$this->subject];
    }

    /**
     * @inheritDoc
     */
    public function asBool(): bool
    {
        return (bool) $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asInt(): int
    {
        return (int) $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return (string) $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->asString();
    }
}
