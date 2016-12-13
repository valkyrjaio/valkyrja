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

use Valkyrja\Contracts\Application;

/**
 * Class StorageConfig
 *
 * @package Valkyrja\Config\Sub
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
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * StorageConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        if ($this->setDefaults) {
            $env = $app->env();

            $this->uploadsDir = $env::STORAGE_UPLOADS_DIR
                ?? $app->storagePath('app');
            $this->logsDir = $env::STORAGE_LOGS_DIR
                ?? $app->storagePath('logs');
        }
    }
}
