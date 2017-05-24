<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Annotations\Providers\AnnotationsServiceProvider;
use Valkyrja\Console\Command;
use Valkyrja\Console\Providers\ConsoleServiceProvider;
use Valkyrja\Container\Container;
use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceAlias;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Contracts\Application;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Events\Events;
use Valkyrja\Events\Listener;
use Valkyrja\Filesystem\Providers\FilesystemServiceProvider;
use Valkyrja\Http\Providers\ClientServiceProvider;
use Valkyrja\Http\Providers\HttpServiceProvider;
use Valkyrja\Http\Providers\JsonResponseServiceProvider;
use Valkyrja\Http\Providers\RedirectResponseServiceProvider;
use Valkyrja\Http\Providers\ResponseBuilderServiceProvider;
use Valkyrja\Logger\Providers\LoggerServiceProvider;
use Valkyrja\Path\Providers\PathServiceProvider;
use Valkyrja\Routing\Providers\RoutingServiceProvider;
use Valkyrja\Routing\Route;
use Valkyrja\Session\Providers\SessionServiceProvider;
use Valkyrja\Support\Directory;
use Valkyrja\View\Providers\ViewServiceProvider;

/*
 *-------------------------------------------------------------------------
 * Framework Default Configurations
 *-------------------------------------------------------------------------
 *
 * We'll need to run the application somehow, and so we'll need certain
 * configuration settings in order to set everything up correctly,
 * and appropriately. Here we have all the configurations for
 * the application, including configurations for each module
 * included in the framework.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Application Configuration
     *-------------------------------------------------------------------------
     *
     * This part of the configuration has to do with the base configuration
     * settings for the application as a whole.
     *
     */
    'app'         => [
        /*
         *-------------------------------------------------------------------------
         * Application Environment
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'env'          => env()::APP_ENV ?? 'production',

        /*
         *-------------------------------------------------------------------------
         * Application Debug
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'debug'        => env()::APP_DEBUG ?? false,

        /*
         *-------------------------------------------------------------------------
         * Application Url
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'url'          => env()::APP_URL ?? 'localhost',

        /*
         *-------------------------------------------------------------------------
         * Application Timezone
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'timezone'     => env()::APP_TIMEZONE ?? 'UTC',

        /*
         *-------------------------------------------------------------------------
         * Application Version
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'version'      => env()::APP_VERSION ?? Application::VERSION,

        /*
         *-------------------------------------------------------------------------
         * Application Container Class
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'container'    => env()::APP_CONTAINER ?? Container::class,

        /*
         *-------------------------------------------------------------------------
         * Application Dispatcher Class
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'dispatcher'   => env()::APP_DISPATCHER ?? Dispatcher::class,

        /*
         *-------------------------------------------------------------------------
         * Application Events Class
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'events'       => env()::APP_EVENTS ?? Events::class,

        /*
         *-------------------------------------------------------------------------
         * Application Path Regex Map
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'pathRegexMap' => env()::APP_PATH_REGEX_MAP ?? [
                'num'                  => '(\d+)',
                'slug'                 => '([a-zA-Z0-9-]+)',
                'alpha'                => '([a-zA-Z]+)',
                'alpha-lowercase'      => '([a-z]+)',
                'alpha-uppercase'      => '([A-Z]+)',
                'alpha-num'            => '([a-zA-Z0-9]+)',
                'alpha-num-underscore' => '(\w+)',
            ],
    ],

    /*
     *-------------------------------------------------------------------------
     * Annotations Configuration
     *-------------------------------------------------------------------------
     *
     * Anything and everything to do with annotations and how they are
     * configured to work within the application can be found here.
     *
     */
    'annotations' => [
        /*
         *-------------------------------------------------------------------------
         * Annotations Enabled
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'enabled'  => env()::ANNOTATIONS_ENABLED ?? false,

        /*
         *-------------------------------------------------------------------------
         * Annotations Cache Dir
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'cacheDir' => env()::ANNOTATIONS_CACHE_DIR ?? Directory::storagePath('vendor/annotations'),

        /*
         *-------------------------------------------------------------------------
         * Annotations Map
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'map'      => env()::ANNOTATIONS_MAP ?? [
                'Command'        => Command::class,
                'Listener'       => Listener::class,
                'Route'          => Route::class,
                'Service'        => Service::class,
                'ServiceAlias'   => ServiceAlias::class,
                'ServiceContext' => ServiceContext::class,
            ],
    ],

    /*
     *-------------------------------------------------------------------------
     * Console Configuration
     *-------------------------------------------------------------------------
     *
     * The console is Valkyrja's module for working with the application
     * through the CLI. All the configurations necessary to make that
     * work can be found here.
     *
     */
    'console'     => [
        /*
         *-------------------------------------------------------------------------
         * Console Use Annotations
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotations'            => env()::CONSOLE_USE_ANNOTATIONS ?? false,

        /*
         *-------------------------------------------------------------------------
         * Console Use Annotations Exclusively
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotationsExclusively' => env()::CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

        /*
         *-------------------------------------------------------------------------
         * Console Handlers
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'handlers'                  => env()::CONSOLE_HANDLERS ?? [],

        /*
         *-------------------------------------------------------------------------
         * Console File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'filePath'                  => env()::CONSOLE_FILE_PATH ?? Directory::basePath('bootstrap/commands.php'),

        /*
         *-------------------------------------------------------------------------
         * Console Cache File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'cacheFilePath'             => env()::CONSOLE_CACHE_FILE_PATH ?? Directory::basePath('framework/cache/commands.php'),

        /*
         *-------------------------------------------------------------------------
         * Console Use Cache File
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useCacheFile'              => env()::CONSOLE_USE_CACHE_FILE ?? true,
    ],

    /*
     *-------------------------------------------------------------------------
     * Container Configuration
     *-------------------------------------------------------------------------
     *
     * The container is the go to place for any type of service the
     * application may need when it is running. All configurations
     * necessary to make it run correctly can be found here.
     *
     */
    'container'   => [
        /*
         *-------------------------------------------------------------------------
         * Container Service Providers
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'providers'                 => env()::CONTAINER_PROVIDERS ?? [],

        /*
         *-------------------------------------------------------------------------
         * Container Core Components Service Providers
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'coreProviders'             => env()::CONTAINER_APP_PROVIDERS ?? [
                AnnotationsServiceProvider::class,
                ClientServiceProvider::class,
                ConsoleServiceProvider::class,
                FilesystemServiceProvider::class,
                HttpServiceProvider::class,
                JsonResponseServiceProvider::class,
                LoggerServiceProvider::class,
                PathServiceProvider::class,
                RedirectResponseServiceProvider::class,
                ResponseBuilderServiceProvider::class,
                RoutingServiceProvider::class,
                SessionServiceProvider::class,
                ViewServiceProvider::class,
            ],

        /*
         *-------------------------------------------------------------------------
         * Container Dev Service Providers
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'devProviders'              => env()::CONTAINER_DEV_PROVIDERS ?? [],

        /*
         *-------------------------------------------------------------------------
         * Container Use Annotations
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotations'            => env()::CONTAINER_USE_ANNOTATIONS ?? false,

        /*
         *-------------------------------------------------------------------------
         * Container Use Annotations Exclusively
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotationsExclusively' => env()::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

        /*
         *-------------------------------------------------------------------------
         * Container Annotated Services
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'services'                  => env()::CONTAINER_SERVICES ?? [],

        /*
         *-------------------------------------------------------------------------
         * Container Annotated Context Services
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'contextServices'           => env()::CONTAINER_CONTEXT_SERVICES ?? [],

        /*
         *-------------------------------------------------------------------------
         * Container Bootstrap File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'filePath'                  => env()::CONTAINER_FILE_PATH ?? Directory::basePath('bootstrap/container.php'),

        /*
         *-------------------------------------------------------------------------
         * Container Cache File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'cacheFilePath'             => env()::CONTAINER_CACHE_FILE_PATH ?? Directory::storagePath('framework/cache/container.php'),

        /*
         *-------------------------------------------------------------------------
         * Container Use Cache File
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useCacheFile'              => env()::CONTAINER_USE_CACHE_FILE ?? true,
    ],

    /*
     *-------------------------------------------------------------------------
     * Events Configuration
     *-------------------------------------------------------------------------
     *
     * Events are a nifty way to tie into certain happenings throughout the
     * application. Found here are all the configurations required to make
     * events work without a hitch.
     *
     */
    'events'      => [
        /*
         *-------------------------------------------------------------------------
         * Events Use Annotations
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotations'            => env()::EVENTS_USE_ANNOTATIONS ?? false,

        /*
         *-------------------------------------------------------------------------
         * Events Use Annotations Exclusively
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotationsExclusively' => env()::EVENTS_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

        /*
         *-------------------------------------------------------------------------
         * Events Annotation Classes
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'classes'                   => env()::EVENTS_CLASSES ?? [],

        /*
         *-------------------------------------------------------------------------
         * Events Bootstrap File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'filePath'                  => env()::EVENTS_FILE_PATH ?? Directory::basePath('bootstrap/events.php'),

        /*
         *-------------------------------------------------------------------------
         * Events Cache File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'cacheFilePath'             => env()::EVENTS_CACHE_FILE_PATH ?? Directory::storagePath('framework/cache/events.php'),

        /*
         *-------------------------------------------------------------------------
         * Events Use Cache File
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useCacheFile'              => env()::EVENTS_USE_CACHE_FILE ?? true,
    ],

    /*
     *-------------------------------------------------------------------------
     * Filesystem Configuration
     *-------------------------------------------------------------------------
     *
     * How the application stores, retrieves, copies, and manipulates files
     * across the filesystem it is located within is a necessity in most
     * applications. Configure that manipulative module here.
     *
     */
    'filesystem'  => [],

    /*
     *-------------------------------------------------------------------------
     * Logger Configuration
     *-------------------------------------------------------------------------
     *
     * Logging is very helpful in understanding what occurs within your
     * application when its deployed and used by multiple users aside
     * from you and your developers. Configure that helpfulness here.
     *
     */
    'logger'      => [
        /*
         *-------------------------------------------------------------------------
         * Logger Log Name
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'name'     => env()::LOGGER_NAME ?? 'ApplicationLog',

        /*
         *-------------------------------------------------------------------------
         * Logger Log File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'filePath' => env()::LOGGER_FILE_PATH ?? Directory::storagePath('logs/valkyrja.log'),
    ],

    /*
     *-------------------------------------------------------------------------
     * Routing Configuration
     *-------------------------------------------------------------------------
     *
     * A pretty big part of getting a user what they've requested is being
     * able to properly route a request through your application. In
     * order to do that you'll need to configure it. Lucky for you
     * all the configurations for routing can be found here.
     *
     */
    'routing'     => [
        /*
         *-------------------------------------------------------------------------
         * Routing Use Trailing Slash
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'trailingSlash'             => env()::ROUTING_TRAILING_SLASH ?? false,

        /*
         *-------------------------------------------------------------------------
         * Routing Use Absolute Urls
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAbsoluteUrls'           => env()::ROUTING_USE_ABSOLUTE_URLS ?? false,

        /*
         *-------------------------------------------------------------------------
         * Routing Use Annotations
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotations'            => env()::ROUTING_USE_ANNOTATIONS ?? false,

        /*
         *-------------------------------------------------------------------------
         * Routing Use Annotations Exclusively
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useAnnotationsExclusively' => env()::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

        /*
         *-------------------------------------------------------------------------
         * Routing Annotation Classes
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'controllers'               => env()::ROUTING_CONTROLLERS ?? [],

        /*
         *-------------------------------------------------------------------------
         * Routing Bootstrap File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'filePath'                  => env()::ROUTING_FILE_PATH ?? Directory::routesPath('routes.php'),

        /*
         *-------------------------------------------------------------------------
         * Routing Cache File Path
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'cacheFilePath'             => env()::ROUTING_CACHE_FILE_PATH ?? Directory::storagePath('framework/cache/routes.php'),

        /*
         *-------------------------------------------------------------------------
         * Routing Use Cache File
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'useCacheFile'              => env()::ROUTING_USE_CACHE_FILE ?? true,
    ],

    /*
     *-------------------------------------------------------------------------
     * Session Configuration
     *-------------------------------------------------------------------------
     *
     * You'll need to keep track of some stuff across requests, and that's
     * where the session comes in handy. Here you'll find all necessary
     * configurations to make the session work properly.
     *
     */
    'session'     => [
        /*
         *-------------------------------------------------------------------------
         * Session Id
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'id'   => env()::SESSION_ID,

        /*
         *-------------------------------------------------------------------------
         * Session Name
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'name' => env()::SESSION_NAME,
    ],

    /*
     *-------------------------------------------------------------------------
     * Storage Configuration
     *-------------------------------------------------------------------------
     *
     * Storage is a necessity when working with any kind of data, whether
     * that be user data, or just application data, there needs to be a
     * place to put all of it. Here you'll find all the configurations
     * that setup the storage of all the things.
     *
     */
    'storage'     => [
        /*
         *-------------------------------------------------------------------------
         * Storage Use Trailing Slash
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'uploadsDir' => env()::STORAGE_UPLOADS_DIR ?? Directory::storagePath('app'),
    ],

    /*
     *-------------------------------------------------------------------------
     * Views Configuration
     *-------------------------------------------------------------------------
     *
     * Views are what provide users with something to look at and enjoy all
     * the hard work you've put into the application. Here you'll find
     * all the configurations necessary to make that work properly.
     *
     */
    'views'       => [
        /*
         *-------------------------------------------------------------------------
         * Views Directory
         *-------------------------------------------------------------------------
         *
         * //
         *
         */
        'dir' => env()::VIEWS_DIR ?? Directory::resourcesPath('views/php'),
    ],
];
