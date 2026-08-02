<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
     */
    public function getValue(): string;

    /**
     * Create a new Option with the specified value.
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
