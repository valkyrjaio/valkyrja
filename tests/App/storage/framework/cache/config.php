<?php return array (
  'app' => 
  array (
    'env' => 'local',
    'debug' => true,
    'url' => 'localhost',
    'timezone' => 'UTC',
    'version' => '1 (ALPHA)',
    'container' => 'Valkyrja\\Container\\Container',
    'dispatcher' => 'Valkyrja\\Dispatcher\\Dispatcher',
    'events' => 'Valkyrja\\Events\\Events',
    'pathRegexMap' => 
    array (
      'num' => '(\\d+)',
      'slug' => '([a-zA-Z0-9-]+)',
      'alpha' => '([a-zA-Z]+)',
      'alpha-lowercase' => '([a-z]+)',
      'alpha-uppercase' => '([A-Z]+)',
      'alpha-num' => '([a-zA-Z0-9]+)',
      'alpha-num-underscore' => '(\\w+)',
    ),
  ),
  'annotations' => 
  array (
    'enabled' => true,
    'cacheDir' => '/var/www/site/storage/vendor/annotations',
    'map' => 
    array (
      'Command' => 'Valkyrja\\Console\\Command',
      'Listener' => 'Valkyrja\\Events\\Listener',
      'Route' => 'Valkyrja\\Routing\\Route',
      'Service' => 'Valkyrja\\Container\\Service',
      'ServiceAlias' => 'Valkyrja\\Container\\ServiceAlias',
      'ServiceContext' => 'Valkyrja\\Container\\ServiceContext',
    ),
  ),
  'console' => 
  array (
    'useAnnotations' => false,
    'useAnnotationsExclusively' => false,
    'handlers' => 
    array (
    ),
    'filePath' => '/var/www/site/bootstrap/commands.php',
    'cacheFilePath' => '/var/www/site/storage/framework/cache/commands.php',
    'useCacheFile' => false,
  ),
  'container' => 
  array (
    'providers' => 
    array (
    ),
    'coreProviders' => 
    array (
      0 => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
      1 => 'Valkyrja\\Http\\Providers\\ClientServiceProvider',
      2 => 'Valkyrja\\Console\\Providers\\ConsoleServiceProvider',
      3 => 'Valkyrja\\Filesystem\\Providers\\FilesystemServiceProvider',
      4 => 'Valkyrja\\Http\\Providers\\HttpServiceProvider',
      5 => 'Valkyrja\\Http\\Providers\\JsonResponseServiceProvider',
      6 => 'Valkyrja\\Logger\\Providers\\LoggerServiceProvider',
      7 => 'Valkyrja\\Path\\Providers\\PathServiceProvider',
      8 => 'Valkyrja\\Http\\Providers\\RedirectResponseServiceProvider',
      9 => 'Valkyrja\\Http\\Providers\\ResponseBuilderServiceProvider',
      10 => 'Valkyrja\\Routing\\Providers\\RoutingServiceProvider',
      11 => 'Valkyrja\\Session\\Providers\\SessionServiceProvider',
      12 => 'Valkyrja\\View\\Providers\\ViewServiceProvider',
    ),
    'devProviders' => 
    array (
    ),
    'useAnnotations' => false,
    'useAnnotationsExclusively' => false,
    'services' => 
    array (
    ),
    'contextServices' => 
    array (
    ),
    'filePath' => '/var/www/site/bootstrap/container.php',
    'cacheFilePath' => '/var/www/site/storage/framework/cache/container.php',
    'useCacheFile' => false,
  ),
  'events' => 
  array (
    'useAnnotations' => false,
    'useAnnotationsExclusively' => false,
    'classes' => 
    array (
    ),
    'filePath' => '/var/www/site/bootstrap/events.php',
    'cacheFilePath' => '/var/www/site/storage/framework/cache/events.php',
    'useCacheFile' => false,
  ),
  'filesystem' => 
  array (
  ),
  'logger' => 
  array (
    'name' => 'ApplicationLog',
    'filePath' => '/var/www/site/storage/logs/valkyrja.log',
  ),
  'routing' => 
  array (
    'trailingSlash' => false,
    'useAbsoluteUrls' => false,
    'useAnnotations' => false,
    'useAnnotationsExclusively' => false,
    'controllers' => 
    array (
    ),
    'filePath' => '/var/www/site/routes/routes.php',
    'cacheFilePath' => '/var/www/site/storage/framework/cache/routes.php',
    'useCacheFile' => false,
  ),
  'session' => 
  array (
    'id' => NULL,
    'name' => NULL,
  ),
  'storage' => 
  array (
    'uploadsDir' => '/var/www/site/storage/app',
  ),
  'views' => 
  array (
    'dir' => '/var/www/site/resources/views/php',
  ),
  'cacheFilePath' => '/var/www/site/storage/framework/cache/config.php',
  'useCacheFile' => true,
);