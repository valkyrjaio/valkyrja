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

namespace Valkyrja\Http\Routing\Data;

use Override;
use Valkyrja\Http\Routing\Constant\Regex as RegexConstant;
use Valkyrja\Http\Routing\Data\Contract\Parameter as Contract;
use Valkyrja\Http\Routing\Exception\InvalidParameterRegexException;
use Valkyrja\Type\Data\Cast;

/**
 * Class Parameter.
 *
 * @author Melech Mizrachi
 */
class Parameter implements Contract
{
    public function __construct(
        protected string $name,
        protected string $regex,
        protected Cast|null $cast = null,
        protected bool $isOptional = false,
        protected bool $shouldCapture = true,
        protected mixed $default = null,
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
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRegex(string $regex): static
    {
        if (@preg_match(RegexConstant::START . $regex . RegexConstant::END, '') === false) {
            throw new InvalidParameterRegexException(
                message: "Invalid parameter regex of `$regex` provided"
            );
        }

        $new = clone $this;

        $new->regex = $regex;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCast(): Cast|null
    {
        return $this->cast;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCast(Cast|null $cast = null): static
    {
        $new = clone $this;

        $new->cast = $cast;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isOptional(): bool
    {
        return $this->isOptional;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withIsOptional(bool $isOptional): static
    {
        $new = clone $this;

        $new->isOptional = $isOptional;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function shouldCapture(): bool
    {
        return $this->shouldCapture;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withShouldCapture(bool $shouldCapture): static
    {
        $new = clone $this;

        $new->shouldCapture = $shouldCapture;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDefault(mixed $default = null): static
    {
        $new = clone $this;

        $new->default = $default;

        return $new;
    }
}
