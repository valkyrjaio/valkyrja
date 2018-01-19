<?php

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
 * Class Command.
 *
 * @author Melech Mizrachi
 */
class Command extends Dispatch
{
    /**
     * The path.
     *
     * @var string
     */
    protected $path;

    /**
     * The regex for dynamic routes.
     *
     * @var string
     */
    protected $regex;

    /**
     * Any params for dynamic routes.
     *
     * @var array
     */
    protected $params;

    /**
     * Any segments for optional parts of path.
     *
     * @var array
     */
    protected $segments;

    /**
     * The description.
     *
     * @var string
     */
    protected $description;

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath(): ? string
    {
        return $this->path;
    }

    /**
     * Set the path.
     *
     * @param string $path The path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): ? string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return $this
     */
    public function setRegex(string $regex = null): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams(): ? array
    {
        return $this->params;
    }

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return $this
     */
    public function setParams(array $params = null): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments(): ? array
    {
        return $this->segments;
    }

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return $this
     */
    public function setSegments(array $segments = null): self
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * Set the description.
     *
     * @param string $description The description
     *
     * @return $this
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }
}
