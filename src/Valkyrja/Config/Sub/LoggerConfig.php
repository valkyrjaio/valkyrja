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
 * Class LoggerConfig.
 *
 * @author Melech Mizrachi
 */
class LoggerConfig
{
    /**
     * The logger name.
     *
     * @var string
     */
    public $name = 'ApplicationLog';

    /**
     * The log file Path.
     *
     * @var string
     */
    public $filePath;

    /**
     * LoggerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->name     = $env::VIEWS_DIR ?? $this->name;
        $this->filePath = $env::VIEWS_DIR ?? Directory::storagePath('logs/valkyrja.log');
    }
}
