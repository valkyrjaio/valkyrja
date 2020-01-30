<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Annotations;

use Valkyrja\Annotation\Annotation;

/**
 * Class Command.
 *
 * @author Melech Mizrachi
 */
class Command extends Annotation
{
    /**
     * The path.
     *
     * @var string|null
     */
    protected ?string $path = null;

    /**
     * The regex for dynamic routes.
     *
     * @var string|null
     */
    protected ?string $regex = null;

    /**
     * Any params for dynamic routes.
     *
     * @var array|null
     */
    protected ?array $params = null;

    /**
     * Any segments for optional parts of path.
     *
     * @var array|null
     */
    protected ?array $segments = null;

    /**
     * The description.
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Set the path.
     *
     * @param string $path The path
     *
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): ?string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return void
     */
    public function setRegex(string $regex = null): void
    {
        $this->regex = $regex;
    }

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return void
     */
    public function setParams(array $params = null): void
    {
        $this->params = $params;
    }

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments(): ?array
    {
        return $this->segments;
    }

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return void
     */
    public function setSegments(array $segments = null): void
    {
        $this->segments = $segments;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description.
     *
     * @param string $description The description
     *
     * @return void
     */
    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }
}
