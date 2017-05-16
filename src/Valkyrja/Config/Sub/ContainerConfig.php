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

use Valkyrja\Annotations\Providers\AnnotationsServiceProvider;
use Valkyrja\Console\Providers\ConsoleServiceProvider;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Filesystem\Providers\FilesystemServiceProvider;
use Valkyrja\Http\Providers\ClientServiceProvider;
use Valkyrja\Logger\Providers\LoggerServiceProvider;
use Valkyrja\Routing\Providers\RoutingServiceProvider;
use Valkyrja\Session\Providers\SessionServiceProvider;
use Valkyrja\Support\Directory;

/**
 * Class ContainerConfig.
 *
 * @author Melech Mizrachi
 */
class ContainerConfig
{
    /**
     * Service providers.
     *
     * @var array
     */
    public $providers = [];

    /**
     * App Service providers.
     *
     * @var array
     */
    public $appProviders = [
        AnnotationsServiceProvider::class,
        ClientServiceProvider::class,
        ConsoleServiceProvider::class,
        FilesystemServiceProvider::class,
        LoggerServiceProvider::class,
        RoutingServiceProvider::class,
        SessionServiceProvider::class,
    ];

    /**
     * Development environment service providers.
     *
     * @var array
     */
    public $devProviders = [];

    /**
     * Use annotations on services?
     *
     * @var bool
     */
    public $useAnnotations = false;

    /**
     * Use only annotations without container file?
     *
     * @var bool
     */
    public $useAnnotationsExclusively = false;

    /**
     * Services to get annotations from.
     *
     * @var array
     */
    public $services = [];

    /**
     * Context services to get annotations from.
     *
     * @var array
     */
    public $contextServices = [];

    /**
     * The container file path.
     *
     * @var string
     */
    public $filePath;

    /**
     * The container cache file path.
     *
     * @var string
     */
    public $cacheFilePath;

    /**
     * Whether to use the container cache file.
     *
     * @var bool
     */
    public $useCacheFile = false;

    /**
     * ContainerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->providers                 = $env::CONTAINER_PROVIDERS
            ?? $this->providers;
        $this->appProviders              = $env::CONTAINER_APP_PROVIDERS
            ?? $this->appProviders;
        $this->devProviders              = $env::CONTAINER_DEV_PROVIDERS
            ?? $this->devProviders;
        $this->useAnnotations            = $env::CONTAINER_USE_ANNOTATIONS
            ?? $this->useAnnotations;
        $this->useAnnotationsExclusively = $env::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY
            ?? $this->useAnnotationsExclusively;
        $this->services                  = $env::CONTAINER_SERVICES
            ?? $this->services;
        $this->contextServices           = $env::CONTAINER_CONTEXT_SERVICES
            ?? $this->contextServices;
        $this->filePath                  = $env::CONTAINER_FILE_PATH
            ?? Directory::basePath('bootstrap/container.php');
        $this->cacheFilePath             = $env::CONTAINER_CACHE_FILE_PATH
            ?? Directory::storagePath('framework/cache/container.php');
        $this->useCacheFile              = $env::CONTAINER_USE_CACHE_FILE
            ?? $this->useCacheFile;
    }
}
