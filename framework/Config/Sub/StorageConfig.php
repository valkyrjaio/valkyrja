<?php

namespace Valkyrja\Config\Sub;

use Valkyrja\Config\Config;

use Valkyrja\Contracts\Application;

class StorageConfig
{
    /**
     * @var string
     */
    public $uploadsDir;

    /**
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
            $this->uploadsDir = Config::env('STORAGE_UPLOADS_DIR') ?? $app->storagePath('app');
            $this->logsDir = Config::env('STORAGE_LOGS_DIR') ?? $app->storagePath('logs');
        }
    }
}
