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

namespace Valkyrja\Http\Message\Header;

use Valkyrja\Http\Message\Header\Contract\Header as Contract;
use Valkyrja\Http\Message\Header\Exception\UnsupportedOffsetSetException;
use Valkyrja\Http\Message\Header\Exception\UnsupportedOffsetUnsetException;
use Valkyrja\Http\Message\Header\Security\HeaderSecurity;
use Valkyrja\Http\Message\Header\Value\Contract\Value;
use Valkyrja\Http\Message\Header\Value\Value as HeaderValue;

use function array_filter;
use function array_map;
use function array_merge;
use function count;
use function explode;
use function implode;
use function is_string;
use function str_contains;
use function strtolower;

/**
 * Class Header.
 *
 * @author Melech Mizrachi
 */
class Header implements Contract
{
    /**
     * Deliminator between name and values.
     *
     * @var non-empty-string
     */
    protected const string DELIMINATOR = ':';

    /**
     * Deliminator for values.
     *
     * @var non-empty-string
     */
    protected const string VALUE_DELIMINATOR = ',';

    /**
     * The header name.
     *
     * @var string
     */
    protected string $name;

    /**
     * The normalized name, useful for comparison.
     *
     * @var string
     */
    protected string $normalizedName;

    /**
     * The values.
     *
     * @var Value[]
     */
    protected array $values = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    /**
     * @param string       $name
     * @param Value|string ...$values
     */
    public function __construct(string $name, Value|string ...$values)
    {
        $this->rewind();
        $this->updateName($name);
        $this->updateValues(...$values);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(string $value): static
    {
        $header         = $value;
        $valuesAsString = '';
        $values         = [];

        if (str_contains($header, static::DELIMINATOR)) {
            [$header, $valuesAsString] = explode(static::DELIMINATOR, $value);
            $values = [$valuesAsString];
        }

        if (str_contains($valuesAsString, static::VALUE_DELIMINATOR)) {
            $values = explode(static::VALUE_DELIMINATOR, $valuesAsString);
        }

        return new static($header, ...$values);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->updateName($name);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @inheritDoc
     */
    public function withValues(Value|string ...$values): static
    {
        $new = clone $this;

        $new->updateValues(...$values);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedValues(Value|string ...$values): static
    {
        $new = $this->withValues(...$values);

        $new->values = array_merge($this->values, $new->values);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getValuesAsString(): string
    {
        return $this->valuesToString();
    }

    /**
     * @inheritDoc
     */
    public function asValue(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->asValue();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->nameToString() . $this->valuesToString();
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->values[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): Value
    {
        return $this->values[$offset];
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
        return count($this->values);
    }

    /**
     * @inheritDoc
     */
    public function current(): Value
    {
        return $this->values[$this->position];
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
        return isset($this->values[$this->position]);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Map string values to Value objects.
     *
     * @param Value|string ...$values
     *
     * @return Value[]
     */
    protected function mapToValue(Value|string ...$values): array
    {
        return array_map(
            static fn (Value|string $value): Value => is_string($value) ? HeaderValue::fromValue($value) : $value,
            $values
        );
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function updateName(string $name): void
    {
        HeaderSecurity::assertValidName($name);

        $this->name = $name;

        $this->normalizedName = strtolower($name);
    }

    protected function updateValues(Value|string ...$values): void
    {
        $this->assertHeaderValues(...$values);
        $this->values = $this->mapToValue(...$values);
    }

    /**
     * @return string
     */
    protected function nameToString(): string
    {
        return $this->name . ':';
    }

    /**
     * @see https://greenbytes.de/tech/webdav/rfc2616.html#message.headers
     *
     * @return string
     */
    protected function valuesToString(): string
    {
        $filteredValues = array_filter($this->values, static fn (Value $value): bool => $value->__toString() !== '');

        return implode(',', $filteredValues);
    }

    /**
     * Filter header values.
     *
     * @param Value|string ...$values Header values
     *
     * @return array<array-key, Value|string>
     */
    protected function assertHeaderValues(Value|string ...$values): array
    {
        foreach ($values as $value) {
            HeaderSecurity::assertValid((string) $value);
        }

        return $values;
    }
}
