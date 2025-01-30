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

namespace Valkyrja\Type;

use Valkyrja\Type\Contract\Type as Contract;

/**
 * Class Type.
 *
 * @author Melech Mizrachi
 *
 * @implements Contract<T>
 *
 * @template T
 */
abstract class Type implements Contract
{
    /**
     * @var T
     */
    protected mixed $subject;

    /**
     * @inheritDoc
     */
    abstract public static function fromValue(mixed $value): static;

    /**
     * @inheritDoc
     */
    public function asValue(): mixed
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function modify(callable $closure): static
    {
        return static::fromValue($closure($this->subject));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->asValue();
    }

    /**
     * @inheritDoc
     */
    abstract public function asFlatValue(): string|int|float|bool|null;
}
