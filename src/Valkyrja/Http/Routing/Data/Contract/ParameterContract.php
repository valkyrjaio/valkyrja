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

namespace Valkyrja\Http\Routing\Data\Contract;

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
     * Create a new parameter with the specified name.
     *
     * @param non-empty-string $name The name
     */
    public function withName(string $name): static;

    /**
     * Get the regex.
     *
     * @return non-empty-string
     */
    public function getRegex(): string;

    /**
     * Create a new parameter with the specified regex.
     *
     * @param non-empty-string $regex The regex
     */
    public function withRegex(string $regex): static;

    /**
     * Get the cast.
     */
    public function getCast(): Cast|null;

    /**
     * Create a new parameter with the specified cast.
     *
     * @param Cast|null $cast The cast
     */
    public function withCast(Cast|null $cast = null): static;

    /**
     * Get whether this parameter is optional.
     */
    public function isOptional(): bool;

    /**
     * Create a new parameter with whether this parameter is optional.
     *
     * @param bool $isOptional Whether this parameter is optional
     */
    public function withIsOptional(bool $isOptional): static;

    /**
     * Get whether this parameter should be captured.
     */
    public function shouldCapture(): bool;

    /**
     * Create a new parameter with whether this parameter should be captured.
     *
     * @param bool $shouldCapture Whether this parameter should be captured
     */
    public function withShouldCapture(bool $shouldCapture): static;

    /**
     * Get the default value.
     *
     * @return array<scalar|object>|scalar|object|null
     */
    public function getDefault(): array|string|int|bool|float|object|null;

    /**
     * Create a new parameter with the specified default value.
     *
     * @param array<scalar|object>|scalar|object|null $default The default value
     */
    public function withDefault(array|string|int|bool|float|object|null $default = null): static;

    /**
     * Get the value.
     *
     * @return array<scalar|object>|scalar|object|null
     */
    public function getValue(): array|string|int|bool|float|object|null;

    /**
     * Create a new parameter with the specified value.
     *
     * @param array<scalar|object>|scalar|object|null $value The value
     */
    public function withValue(array|string|int|bool|float|object|null $value = null): static;
}
