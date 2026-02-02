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
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Type\Data\Cast;

class Parameter implements ParameterContract
{
    /**
     * @param non-empty-string                        $name    The parameter's name
     * @param non-empty-string                        $regex   The parameter's regex
     * @param array<scalar|object>|scalar|object|null $default The default value
     * @param array<scalar|object>|scalar|object|null $value   The value
     */
    public function __construct(
        protected string $name,
        protected string $regex,
        protected Cast|null $cast = null,
        protected bool $isOptional = false,
        protected bool $shouldCapture = true,
        protected array|string|int|bool|float|object|null $default = null,
        protected array|string|int|bool|float|object|null $value = null,
    ) {
    }

    /**
     * @param array{
     *     name: non-empty-string,
     *     regex: non-empty-string,
     *     cast: Cast|null,
     *     isOptional: bool,
     *     shouldCapture: bool,
     *     default: array<scalar|object>|scalar|object|null,
     *     value: array<scalar|object>|scalar|object|null,
     * } $array The array
     */
    public static function __set_state(array $array): static
    {
        return new static(
            name: $array['name'],
            regex: $array['regex'],
            cast: $array['cast'] ?? null,
            isOptional: $array['isOptional'],
            shouldCapture: $array['shouldCapture'],
            default: $array['default'] ?? null,
            value: $array['value'] ?? null
        );
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
    public function getDefault(): array|string|int|bool|float|object|null
    {
        return $this->default;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDefault(array|string|int|bool|float|object|null $default = null): static
    {
        $new = clone $this;

        $new->default = $default;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValue(): array|string|int|bool|float|object|null
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValue(array|string|int|bool|float|object|null $value = null): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }
}
