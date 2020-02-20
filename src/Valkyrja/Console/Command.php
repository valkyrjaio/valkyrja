<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
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
     * @return string
     */
    public function getPath(): ?string;

    /**
     * Set the path.
     *
     * @param string $path The path
     *
     * @return $this
     */
    public function setPath(string $path): self;

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): ?string;

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return $this
     */
    public function setRegex(string $regex = null): self;

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams(): ?array;

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return $this
     */
    public function setParams(array $params = null): self;

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments(): ?array;

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return $this
     */
    public function setSegments(array $segments = null): self;

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * Set the description.
     *
     * @param string $description The description
     *
     * @return $this
     */
    public function setDescription(string $description = null): self;
}
