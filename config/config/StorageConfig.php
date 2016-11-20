<?php

namespace config\config;

use config\Configs;

use Valkyrja\Application;

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
     * StorageConfig constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        $this->uploadsDir = Configs::env('STORAGE_UPLOADS_DIR') ?? $app->storagePath('app');
        $this->logsDir = Configs::env('STORAGE_LOGS_DIR') ?? $app->storagePath('logs');
    }
}
