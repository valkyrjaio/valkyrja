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

namespace Valkyrja\Type\Abstract;

use Override;
use Valkyrja\Type\Contract\TypeContract;

/**
 * @template T of scalar|object|array<array-key, mixed>|null
 *
 * @implements TypeContract<T>
 */
abstract class Type implements TypeContract
{
    /**
     * @var T
     */
    protected mixed $subject;

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public static function fromValue(mixed $value): static;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): mixed
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function modify(callable $closure): static
    {
        return static::fromValue($closure($this->subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): mixed
    {
        return $this->asValue();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function asFlatValue(): string|int|float|bool|null;
}
