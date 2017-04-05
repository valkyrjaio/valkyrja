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
 * Class LoggerConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author  Melech Mizrachi
 */
class LoggerConfig
{
    /**
     * The logger name.
     *
     * @var string
     */
    public $name;

    /**
     * The log file Path.
     *
     * @var string
     */
    public $filePath;

    /**
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * LoggerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        if ($this->setDefaults) {
            $this->name = $env::VIEWS_DIR ?? 'ApplicationLog';

            $this->filePath = $env::VIEWS_DIR ?? Directory::storagePath('logs/valkyrja.log');
        }
    }
}
