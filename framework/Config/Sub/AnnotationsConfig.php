<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Sub;

use Valkyrja\Contracts\Config\Env;
use Valkyrja\Support\Directory;

/**
 * Class AnnotationsConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author Melech Mizrachi
 */
class AnnotationsConfig
{
    /**
     * Enable annotations?
     *
     * @var bool
     */
    public $enabled = false;

    /**
     * Cache directory to use for annotations.
     *
     * @var string
     */
    public $cacheDir;

    /**
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * AnnotationsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        if ($this->setDefaults) {
            $this->enabled = $env::ANNOTATIONS_ENABLED ?? false;
            $this->cacheDir = Directory::storagePath('vendor/annotations');
        }
    }
}
