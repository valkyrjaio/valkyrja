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

/**
 * Interface OptionContract.
 *
 * @author Melech Mizrachi
 */
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
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the value.
     *
     * @return non-empty-string|null
     */
    public function getValue(): string|null;

    /**
     * Create a new Option with the specified value.
     *
     * @param non-empty-string|null $value The value
     *
     * @return static
     */
    public function withValue(string|null $value): static;

    /**
     * Get the option type.
     *
     * @return OptionType
     */
    public function getType(): OptionType;

    /**
     * Create a new Option with the specified type.
     *
     * @param OptionType $type The option type
     *
     * @return static
     */
    public function withType(OptionType $type): static;
}
