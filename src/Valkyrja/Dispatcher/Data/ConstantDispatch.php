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

use Override;
use Valkyrja\Dispatcher\Data\Contract\ConstantDispatch as Contract;

/**
 * Class ConstantDispatch.
 *
 * @author Melech Mizrachi
 */
class ConstantDispatch extends Dispatch implements Contract
{
    /**
     * @param non-empty-string  $constant The constant name
     * @param class-string|null $class    The class name
     */
    public function __construct(
        protected string $constant,
        protected string|null $class = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getConstant(): string
    {
        return $this->constant;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withConstant(string $constant): static
    {
        $new = clone $this;

        $new->constant = $constant;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getClass(): string|null
    {
        return $this->class;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withClass(string|null $class = null): static
    {
        $new = clone $this;

        $new->class = $class;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __toString(): string
    {
        return ($this->class !== null ? $this->class . '::' : '')
            . $this->constant;
    }
}
