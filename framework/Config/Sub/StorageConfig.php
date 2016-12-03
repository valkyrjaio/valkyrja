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
use Valkyrja\Support\Helpers;

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
            $this->uploadsDir = Helpers::env('STORAGE_UPLOADS_DIR') ?? $app->storagePath('app');
            $this->logsDir = Helpers::env('STORAGE_LOGS_DIR') ?? $app->storagePath('logs');
        }
    }
}
