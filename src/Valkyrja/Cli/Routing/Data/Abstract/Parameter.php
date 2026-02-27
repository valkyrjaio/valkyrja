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

namespace Valkyrja\Cli\Routing\Data\Abstract;

use Override;
use Valkyrja\Cli\Routing\Data\Contract\ParameterContract;
use Valkyrja\Cli\Routing\Throwable\Exception\NoCastException;
use Valkyrja\Type\Data\Cast;

abstract class Parameter implements ParameterContract
{
    /**
     * @param non-empty-string $name        The name
     * @param non-empty-string $description The description
     */
    public function __construct(
        protected string $name,
        protected string $description,
        protected Cast|null $cast = null,
    ) {
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
    public function hasCast(): bool
    {
        return $this->cast !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCast(): Cast
    {
        return $this->cast
            ?? throw new NoCastException('No cast exists');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCast(Cast $cast): static
    {
        $new = clone $this;

        $new->cast = $cast;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutCast(): static
    {
        $new = clone $this;

        $new->cast = null;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDescription(string $description): static
    {
        $new = clone $this;

        $new->description = $description;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function getCastValues(): array;
}
