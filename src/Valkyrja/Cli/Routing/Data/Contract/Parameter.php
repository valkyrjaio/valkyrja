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

/**
 * Interface Parameter.
 *
 * @author Melech Mizrachi
 */
interface Parameter
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
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the cast.
     *
     * @return Cast|null
     */
    public function getCast(): Cast|null;

    /**
     * Create a new Parameter with the specified cast.
     *
     * @param Cast|null $cast The cast
     *
     * @return static
     */
    public function withCast(Cast|null $cast = null): static;

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
     *
     * @return static
     */
    public function withDescription(string $description): static;

    /**
     * Get all the values cast with the cast (if one is present).
     *
     * @return array<array-key, mixed>
     */
    public function getCastValues(): array;

    /**
     * Get the first value.
     *
     * @return non-empty-string|null
     */
    public function getFirstValue(): string|null;

    /**
     * Determine if the values are valid.
     *
     * @return bool
     */
    public function areValuesValid(): bool;

    /**
     * Validate the values.
     *
     * @return static
     */
    public function validateValues(): static;
}
