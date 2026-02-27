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

namespace Valkyrja\Cli\Routing\Data;

use Override;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;
use Valkyrja\Cli\Routing\Data\Abstract\Parameter;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Cli\Routing\Throwable\Exception\NoDefaultValueException;
use Valkyrja\Cli\Routing\Throwable\Exception\NoFirstValueException;
use Valkyrja\Cli\Routing\Throwable\Exception\NoValueDisplayNameException;
use Valkyrja\Type\Data\Cast;

use function count;
use function in_array;

class OptionParameter extends Parameter implements OptionParameterContract
{
    /**
     * @param non-empty-string      $name             The names
     * @param non-empty-string      $description      The description
     * @param non-empty-string|null $valueDisplayName The value display name
     * @param non-empty-string|null $defaultValue     The default value
     * @param non-empty-string[]    $shortNames       The short names
     * @param non-empty-string[]    $validValues      The valid values
     * @param OptionContract[]      $options          The options
     */
    public function __construct(
        string $name,
        string $description,
        protected string|null $valueDisplayName = null,
        Cast|null $cast = null,
        protected string|null $defaultValue = null,
        protected array $shortNames = [],
        protected array $validValues = [],
        protected array $options = [],
        protected OptionMode $mode = OptionMode::OPTIONAL,
        protected OptionValueMode $valueMode = OptionValueMode::DEFAULT,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            cast: $cast
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getShortNames(): array
    {
        return $this->shortNames;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withShortNames(string ...$shortNames): static
    {
        $new = clone $this;

        $new->shortNames = $shortNames;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedShortNames(string ...$shortNames): static
    {
        $new = clone $this;

        foreach ($shortNames as $shortName) {
            if (! in_array($shortName, $new->shortNames, true)) {
                $new->shortNames[] = $shortName;
            }
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMode(): OptionMode
    {
        return $this->mode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withMode(OptionMode $mode): static
    {
        $new = clone $this;

        $new->mode = $mode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValueMode(): OptionValueMode
    {
        return $this->valueMode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValueMode(OptionValueMode $valueMode): static
    {
        $new = clone $this;

        $new->valueMode = $valueMode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasValueDisplayName(): bool
    {
        return $this->valueDisplayName !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValueDisplayName(): string
    {
        return $this->valueDisplayName
            ?? throw new NoValueDisplayNameException('No value display name exists');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValueDisplayName(string $valueName): static
    {
        $new = clone $this;

        $new->valueDisplayName = $valueName;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutValueDisplayName(): static
    {
        $new = clone $this;

        $new->valueDisplayName = null;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValidValues(): array
    {
        return $this->validValues;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValidValues(string ...$validValues): static
    {
        $new = clone $this;

        $new->validValues = $validValues;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedValidValues(string ...$validValues): static
    {
        $new = clone $this;

        foreach ($validValues as $validValue) {
            if (! in_array($validValue, $new->validValues, true)) {
                $new->validValues[] = $validValue;
            }
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasDefaultValue(): bool
    {
        return $this->defaultValue !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefaultValue(): string
    {
        return $this->defaultValue
            ?? throw new NoDefaultValueException('No default value exists');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDefaultValue(string $defaultValue): static
    {
        $new = clone $this;

        $new->defaultValue = $defaultValue;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutDefaultValue(): static
    {
        $new = clone $this;

        $new->defaultValue = null;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withOptions(OptionContract ...$options): static
    {
        $new = clone $this;

        $new->options = [];

        foreach ($options as $option) {
            if ($this->valueMode === OptionValueMode::NONE && $option->hasValue()) {
                throw new InvalidArgumentException("$this->name should have no value");
            }

            $new->options[] = $option;
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedOptions(OptionContract ...$options): static
    {
        $new = clone $this;

        foreach ($options as $option) {
            if ($this->valueMode === OptionValueMode::NONE && $option->hasValue()) {
                throw new InvalidArgumentException("$this->name should have no value");
            }

            $new->options[] = $option;
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCastValues(): array
    {
        $values   = [];
        $cast     = $this->cast;
        $castType = $cast->type ?? null;

        foreach ($this->options as $option) {
            $optionValue = $option->hasValue()
                ? $option->getValue()
                : null;

            if ($cast === null || $castType === null) {
                $values[] = $optionValue;

                continue;
            }

            $value = $castType::fromValue($optionValue);

            if ($cast->convert) {
                $values[] = $value->asValue();

                continue;
            }

            $values[] = $value;
        }

        return $values;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasFirstValue(): bool
    {
        return isset($this->options[0]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFirstValue(): string
    {
        $firstItem = $this->options[0] ?? null;

        if ($firstItem === null || ! $firstItem->hasValue()) {
            throw new NoFirstValueException('No first value exists');
        }

        return $firstItem->getValue();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function areValuesValid(): bool
    {
        $valid = true;

        if ($this->mode === OptionMode::REQUIRED) {
            $valid = $this->options !== [];
        }

        if ($this->valueMode === OptionValueMode::DEFAULT) {
            $valid = $valid && count($this->options) <= 1;
        }

        return $valid;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function validateValues(): static
    {
        if (! $this->areValuesValid()) {
            throw new InvalidArgumentException("$this->name is required");
        }

        return $this;
    }
}
