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

use Override;
use Valkyrja\Http\Message\Header\Contract\HeaderContract as Contract;
use Valkyrja\Http\Message\Header\Security\HeaderSecurity;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetSetException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedOffsetUnsetException;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;
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
     * @var ValueContract[]
     */
    protected array $values = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    public function __construct(string $name, ValueContract|string ...$values)
    {
        $this->rewind();
        $this->updateName($name);
        $this->updateValues(...$values);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(string $value): static
    {
        $header         = $value;
        $valuesAsString = '';
        $values         = [];

        if (str_contains($header, static::DELIMINATOR)) {
            [$header, $valuesAsString] = explode(static::DELIMINATOR, $value);
            $values                    = [$valuesAsString];
        }

        if (str_contains($valuesAsString, static::VALUE_DELIMINATOR)) {
            $values = explode(static::VALUE_DELIMINATOR, $valuesAsString);
        }

        return new static($header, ...$values);
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
    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->updateName($name);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValues(ValueContract|string ...$values): static
    {
        $new = clone $this;

        $new->updateValues(...$values);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedValues(ValueContract|string ...$values): static
    {
        $new = $this->withValues(...$values);

        $new->values = array_merge($this->values, $new->values);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValuesAsString(): string
    {
        return $this->valuesToString();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->values[$offset]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetGet(mixed $offset): ValueContract
    {
        return $this->values[$offset];
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
        return count($this->values);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function current(): ValueContract
    {
        return $this->values[$this->position];
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
        return isset($this->values[$this->position]);
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
     * Map string values to Value objects.
     *
     *
     * @return ValueContract[]
     */
    protected function mapToValue(ValueContract|string ...$values): array
    {
        return array_map(
            static fn (ValueContract|string $value): ValueContract => is_string($value) ? HeaderValue::fromValue($value) : $value,
            $values
        );
    }

    protected function updateName(string $name): void
    {
        HeaderSecurity::assertValidName($name);

        $this->name = $name;

        $this->normalizedName = strtolower($name);
    }

    protected function updateValues(ValueContract|string ...$values): void
    {
        $this->assertHeaderValues(...$values);
        $this->values = $this->mapToValue(...$values);
    }

    protected function nameToString(): string
    {
        return $this->name . ':';
    }

    /**
     * @see https://greenbytes.de/tech/webdav/rfc2616.html#message.headers
     */
    protected function valuesToString(): string
    {
        $filteredValues = array_filter($this->values, static fn (ValueContract $value): bool => $value->__toString() !== '');

        return implode(',', $filteredValues);
    }

    /**
     * Filter header values.
     *
     * @param ValueContract|string ...$values Header values
     *
     * @return array<array-key, ValueContract|string>
     */
    protected function assertHeaderValues(ValueContract|string ...$values): array
    {
        foreach ($values as $value) {
            HeaderSecurity::assertValid((string) $value);
        }

        return $values;
    }
}
