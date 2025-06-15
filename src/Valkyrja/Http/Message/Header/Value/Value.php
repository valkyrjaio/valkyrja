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

use Valkyrja\Http\Message\Header\Exception\UnsupportedOffsetSetException;
use Valkyrja\Http\Message\Header\Exception\UnsupportedOffsetUnsetException;
use Valkyrja\Http\Message\Header\Value\Component\Component as HeaderPart;
use Valkyrja\Http\Message\Header\Value\Component\Contract\Component;
use Valkyrja\Http\Message\Header\Value\Contract\Value as Contract;

use function array_filter;
use function array_map;
use function array_merge;
use function count;
use function explode;
use function implode;
use function is_string;

/**
 * Class Value.
 *
 * @author Melech Mizrachi
 */
class Value implements Contract
{
    /**
     * Deliminator to use for value components.
     *
     * @var non-empty-string
     */
    protected const string DELIMINATOR = ';';

    /**
     * @var Component[]
     */
    protected array $components = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    /**
     * @param Component|string ...$components
     */
    public function __construct(Component|string ...$components)
    {
        $this->components = $this->mapToPart(...$components);
    }

    /**
     * @inheritDoc
     */
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
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @inheritDoc
     */
    public function withComponents(Component|string ...$components): static
    {
        $new = clone $this;

        $new->components = $this->mapToPart(...$components);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedComponents(Component|string ...$components): static
    {
        $new = $this->withComponents(...$components);

        $new->components = array_merge($this->components, $new->components);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $filteredParts = array_filter($this->components, static fn (Component $component): bool => $component->__toString() !== '');

        return implode(';', $filteredParts);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->components[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): Component
    {
        return $this->components[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new UnsupportedOffsetSetException('Use withValues or withAddedValues');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new UnsupportedOffsetUnsetException('Use withValues or withAddedValues');
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->components);
    }

    /**
     * @inheritDoc
     */
    public function current(): Component
    {
        return $this->components[$this->position];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @inheritDoc
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->components[$this->position]);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Map string parts to Part objects.
     *
     * @param Component|string ...$parts
     *
     * @return Component[]
     */
    protected function mapToPart(Component|string ...$parts): array
    {
        return array_map(
            static fn (Component|string $part): Component => is_string($part) ? HeaderPart::fromValue($part) : $part,
            $parts
        );
    }
}
