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

namespace Valkyrja\Cli\Interaction\Option\Contract;

use Valkyrja\Cli\Interaction\Enum\OptionType;

interface OptionContract
{
    /**
     * Get the name.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Create a new Option with the specified name.
     *
     * @param non-empty-string $name The name
     */
    public function withName(string $name): static;

    /**
     * Determine if a value exists.
     */
    public function hasValue(): bool;

    /**
     * Get the value.
     *
     * @return non-empty-string
     */
    public function getValue(): string;

    /**
     * Create a new Option with the specified value.
     *
     * @param non-empty-string $value The value
     */
    public function withValue(string $value): static;

    /**
     * Create a new Option without a value.
     */
    public function withoutValue(): static;

    /**
     * Get the option type.
     */
    public function getType(): OptionType;

    /**
     * Create a new Option with the specified type.
     *
     * @param OptionType $type The option type
     */
    public function withType(OptionType $type): static;
}
