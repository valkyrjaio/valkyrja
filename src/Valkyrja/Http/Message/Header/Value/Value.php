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

namespace Valkyrja\Http\Message\Header\Value;

use Override;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetSetException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetUnsetException;
use Valkyrja\Http\Message\Header\Value\Component\Component;
use Valkyrja\Http\Message\Header\Value\Component\Contract\ComponentContract;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

use function array_filter;
use function array_map;
use function array_merge;
use function count;
use function explode;
use function implode;
use function is_string;

class Value implements ValueContract
{
    /**
     * Deliminator to use for value components.
     *
     * @var non-empty-string
     */
    protected const string DELIMINATOR = ';';

    /**
     * @var array<array-key, ComponentContract|string>
     */
    protected array $components = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    public function __construct(ComponentContract|string ...$components)
    {
        $this->components = $this->mapToPart(...$components);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(string $value): static
    {
        $parts = [$value];

        if (str_contains($value, static::DELIMINATOR)) {
            $parts = explode(static::DELIMINATOR, $value);
        }

        return new static(...$parts);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withComponents(ComponentContract|string ...$components): static
    {
        $new = clone $this;

        $new->components = $this->mapToPart(...$components);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedComponents(ComponentContract|string ...$components): static
    {
        $new = $this->withComponents(...$components);

        $new->components = array_merge($this->components, $new->components);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $filteredParts = array_filter(
            $this->components,
            static fn (ComponentContract|string $component): bool => (is_string($component) ? $component : $component->__toString()) !== ''
        );

        return implode(';', $filteredParts);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->components[$offset]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetGet(mixed $offset): ComponentContract|string
    {
        return $this->components[$offset];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new UnsupportedOffsetSetException('Use withValues or withAddedValues');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        throw new UnsupportedOffsetUnsetException('Use withValues or withAddedValues');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function count(): int
    {
        return count($this->components);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function current(): ComponentContract|string
    {
        return $this->components[$this->position];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function valid(): bool
    {
        return isset($this->components[$this->position]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Map string parts to Part objects.
     *
     * @return ComponentContract[]
     */
    protected function mapToPart(ComponentContract|string ...$parts): array
    {
        return array_map(
            static fn (ComponentContract|string $part): ComponentContract => is_string($part) ? Component::fromValue($part) : $part,
            $parts
        );
    }
}
