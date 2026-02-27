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

namespace Valkyrja\Cli\Routing\Data\Contract;

use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

interface OptionParameterContract extends ParameterContract
{
    /**
     * Get the short names.
     *
     * @return non-empty-string[]
     */
    public function getShortNames(): array;

    /**
     * Create a new Option parameter with the specified short names.
     *
     * @param non-empty-string ...$shortNames The short names
     */
    public function withShortNames(string ...$shortNames): static;

    /**
     * Create a new Option parameter with added short names.
     *
     * @param non-empty-string ...$shortNames The short names
     */
    public function withAddedShortNames(string ...$shortNames): static;

    /**
     * Get the option mode.
     */
    public function getMode(): OptionMode;

    /**
     * Create a new Option parameter with the specified mode.
     *
     * @param OptionMode $mode The mode
     */
    public function withMode(OptionMode $mode): static;

    /**
     * Get the specified value mode.
     */
    public function getValueMode(): OptionValueMode;

    /**
     * Create a new Option parameter with the specified value mode.
     *
     * @param OptionValueMode $valueMode The value mode
     */
    public function withValueMode(OptionValueMode $valueMode): static;

    /**
     * Determine if there is a value display name.
     */
    public function hasValueDisplayName(): bool;

    /**
     * Get the value display name.
     *
     * @return non-empty-string
     */
    public function getValueDisplayName(): string;

    /**
     * Create a new Option parameter with the specified value display name.
     *
     * @param non-empty-string $valueName The value name
     */
    public function withValueDisplayName(string $valueName): static;

    /**
     * Create a new Option parameter without a value display name.
     */
    public function withoutValueDisplayName(): static;

    /**
     * Get the valid values.
     *
     * @return non-empty-string[]
     */
    public function getValidValues(): array;

    /**
     * Create a new Option parameter with the specified valid values.
     *
     * @param non-empty-string ...$validValues The valid values
     */
    public function withValidValues(string ...$validValues): static;

    /**
     * Create a new Option parameter with added valid values.
     *
     * @param non-empty-string ...$validValues The valid values
     */
    public function withAddedValidValues(string ...$validValues): static;

    /**
     * Determine if there is a default value.
     */
    public function hasDefaultValue(): bool;

    /**
     * Get the default value.
     *
     * @return non-empty-string
     */
    public function getDefaultValue(): string;

    /**
     * Create a new Option parameter with the specified default value.
     *
     * @param non-empty-string $defaultValue The default value
     */
    public function withDefaultValue(string $defaultValue): static;

    /**
     * Create a new Option parameter without a default value.
     */
    public function withoutDefaultValue(): static;

    /**
     * Get the options.
     *
     * @return OptionContract[]
     */
    public function getOptions(): array;

    /**
     * Create a new Option parameter with the specified options.
     *
     * @param OptionContract ...$options The options
     */
    public function withOptions(OptionContract ...$options): static;

    /**
     * Create a new Option parameter with added options.
     *
     * @param OptionContract ...$options The options
     */
    public function withAddedOptions(OptionContract ...$options): static;
}
