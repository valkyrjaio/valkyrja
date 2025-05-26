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

use JsonSerializable;
use Stringable;
use Valkyrja\Type\Data\Cast;

/**
 * Interface Parameter.
 *
 * @author Melech Mizrachi
 */
interface Parameter extends JsonSerializable, Stringable
{
    /**
     * Create a new route from an array of data.
     *
     * @param array<string, mixed> $data The data
     *
     * @return static
     */
    public static function fromArray(array $data): static;

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Create a new parameter with the specified name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): string;

    /**
     * Create a new parameter with the specified regex.
     *
     * @param string $regex The regex
     *
     * @return static
     */
    public function withRegex(string $regex): static;

    /**
     * Get the cast.
     *
     * @return Cast|null
     */
    public function getCast(): Cast|null;

    /**
     * Create a new parameter with the specified cast.
     *
     * @param Cast|null $cast The cast
     *
     * @return static
     */
    public function withCast(Cast|null $cast = null): static;

    /**
     * Get whether this parameter is optional.
     *
     * @return bool
     */
    public function isOptional(): bool;

    /**
     * Create a new parameter with whether this parameter is optional.
     *
     * @param bool $isOptional Whether this parameter is optional
     *
     * @return static
     */
    public function withIsOptional(bool $isOptional): static;

    /**
     * Get whether this parameter should be captured.
     *
     * @return bool
     */
    public function shouldCapture(): bool;

    /**
     * Create a new parameter with whether this parameter should be captured.
     *
     * @param bool $shouldCapture Whether this parameter should be captured
     *
     * @return static
     */
    public function withShouldCapture(bool $shouldCapture): static;

    /**
     * Get the default value.
     *
     * @return mixed
     */
    public function getDefault(): mixed;

    /**
     * Create a new parameter with the specified default value.
     *
     * @param mixed $default The default value
     *
     * @return static
     */
    public function withDefault(mixed $default = null): static;

    /**
     * Get the Dispatch as a string.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Serialize properties for json_encode.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array;
}
