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

namespace Valkyrja\Console;

use Valkyrja\Dispatcher\Dispatch;

/**
 * Interface Command.
 *
 * @author Melech Mizrachi
 */
interface Command extends Dispatch
{
    /**
     * Get the path.
     *
     * @return string|null
     */
    public function getPath(): string|null;

    /**
     * Set the path.
     *
     * @param string $path The path
     *
     * @return static
     */
    public function setPath(string $path): static;

    /**
     * Get the regex.
     *
     * @return string|null
     */
    public function getRegex(): string|null;

    /**
     * Set the regex.
     *
     * @param string|null $regex The regex
     *
     * @return static
     */
    public function setRegex(string $regex = null): static;

    /**
     * Get the params.
     *
     * @return array|null
     */
    public function getParams(): array|null;

    /**
     * Set the params.
     *
     * @param array|null $params The params
     *
     * @return static
     */
    public function setParams(array $params = null): static;

    /**
     * Get the segments.
     *
     * @return array|null
     */
    public function getSegments(): array|null;

    /**
     * Set the segments.
     *
     * @param array|null $segments The segments
     *
     * @return static
     */
    public function setSegments(array $segments = null): static;

    /**
     * Get the description.
     *
     * @return string|null
     */
    public function getDescription(): string|null;

    /**
     * Set the description.
     *
     * @param string|null $description The description
     *
     * @return static
     */
    public function setDescription(string $description = null): static;
}
