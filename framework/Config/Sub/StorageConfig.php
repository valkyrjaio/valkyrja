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
 * Class StorageConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author  Melech Mizrachi
 */
class StorageConfig
{
    /**
     * Upload directory.
     *
     * @var string
     */
    public $uploadsDir;

    /**
     * Logs directory.
     *
     * @var string
     */
    public $logsDir;

    /**
     * StorageConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->uploadsDir = $env::STORAGE_UPLOADS_DIR ?? Directory::storagePath('app');
        $this->logsDir = $env::STORAGE_LOGS_DIR ?? Directory::storagePath('logs');
    }
}
