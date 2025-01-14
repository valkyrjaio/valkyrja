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

namespace Valkyrja\Dispatcher\Data;

use Valkyrja\Dispatcher\Data\Contract\ConstantDispatch as Contract;

/**
 * Class ConstantDispatch.
 *
 * @author Melech Mizrachi
 *
 * @phpstan-consistent-constructor
 *   Will be overridden if need be
 */
class ConstantDispatch extends Dispatch implements Contract
{
    /**
     * @param class-string|null $class The class name
     */
    public function __construct(
        protected string $constant,
        protected string|null $class = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    /**
     * @inheritDoc
     */
    public function getConstant(): string
    {
        return $this->constant;
    }

    /**
     * @inheritDoc
     */
    public function withConstant(string $constant): static
    {
        $new = clone $this;

        $new->constant = $constant;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getClass(): string|null
    {
        return $this->class;
    }

    /**
     * @inheritDoc
     */
    public function withClass(string|null $class = null): static
    {
        $new = clone $this;

        $new->class = $class;

        return $new;
    }
}
