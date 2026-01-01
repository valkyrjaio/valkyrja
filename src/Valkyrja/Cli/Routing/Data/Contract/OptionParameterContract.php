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
     *
     * @return static
     */
    public function withShortNames(string ...$shortNames): static;

    /**
     * Create a new Option parameter with added short names.
     *
     * @param non-empty-string ...$shortNames The short names
     *
     * @return static
     */
    public function withAddedShortNames(string ...$shortNames): static;

    /**
     * Get the option mode.
     *
     * @return OptionMode
     */
    public function getMode(): OptionMode;

    /**
     * Create a new Option parameter with the specified mode.
     *
     * @param OptionMode $mode The mode
     *
     * @return static
     */
    public function withMode(OptionMode $mode): static;

    /**
     * Get the specified value mode.
     *
     * @return OptionValueMode
     */
    public function getValueMode(): OptionValueMode;

    /**
     * Create a new Option parameter with the specified value mode.
     *
     * @param OptionValueMode $valueMode The value mode
     *
     * @return static
     */
    public function withValueMode(OptionValueMode $valueMode): static;

    /**
     * Get the value display name.
     *
     * @return non-empty-string|null
     */
    public function getValueDisplayName(): string|null;

    /**
     * Create a new Option parameter with the specified value display name.
     *
     * @param non-empty-string|null $valueName The value name
     *
     * @return static
     */
    public function withValueDisplayName(string|null $valueName): static;

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
     *
     * @return static
     */
    public function withValidValues(string ...$validValues): static;

    /**
     * Create a new Option parameter with added valid values.
     *
     * @param non-empty-string ...$validValues The valid values
     *
     * @return static
     */
    public function withAddedValidValues(string ...$validValues): static;

    /**
     * Get the default value.
     *
     * @return non-empty-string|null
     */
    public function getDefaultValue(): string|null;

    /**
     * Create a new Option parameter with the specified default value.
     *
     * @param non-empty-string|null $defaultValue The default value
     *
     * @return static
     */
    public function withDefaultValue(string|null $defaultValue = null): static;

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
     *
     * @return static
     */
    public function withOptions(OptionContract ...$options): static;

    /**
     * Create a new Option parameter with added options.
     *
     * @param OptionContract ...$options The options
     *
     * @return static
     */
    public function withAddedOptions(OptionContract ...$options): static;
}
