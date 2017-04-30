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

use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Class Command
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Command extends Dispatch implements Annotation
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
     * Get the path.
     *
     * @return string
     */
    public function getPath():? string
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
    public function getRegex():? string
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
    public function getParams():? array
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
}
