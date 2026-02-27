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

use Valkyrja\Type\Data\Cast;

interface ParameterContract
{
    /**
     * Get the name.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Create a new Parameter with the specified name.
     *
     * @param non-empty-string $name The name
     */
    public function withName(string $name): static;

    /**
     * Determine if a cast exists.
     */
    public function hasCast(): bool;

    /**
     * Get the cast.
     */
    public function getCast(): Cast;

    /**
     * Create a new Parameter with the specified cast.
     *
     * @param Cast $cast The cast
     */
    public function withCast(Cast $cast): static;

    /**
     * Create a new Parameter without a cast.
     */
    public function withoutCast(): static;

    /**
     * Get the description.
     *
     * @return non-empty-string
     */
    public function getDescription(): string;

    /**
     * Create a new Parameter with the specified description.
     *
     * @param non-empty-string $description The description
     */
    public function withDescription(string $description): static;

    /**
     * Get all the values cast with the cast (if one is present).
     *
     * @return array<array-key, mixed>
     */
    public function getCastValues(): array;

    /**
     * Determine if there is a first item value.
     */
    public function hasFirstValue(): bool;

    /**
     * Get the first item's value.
     *
     * @return non-empty-string
     */
    public function getFirstValue(): string;

    /**
     * Determine if the values are valid.
     */
    public function areValuesValid(): bool;

    /**
     * Validate the values.
     */
    public function validateValues(): static;
}
