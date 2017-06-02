<?php return array (
  'commands' => 'YTowOnt9',
  'paths' => 
  array (
    '/^config:cache$/' => 'config:cache',
    '/^cache:all(?: (-s|--sync))?$/' => 'cache:all[ {sync:-s|--sync}]',
    '/^(?:commands)?(?: ([a-zA-Z0-9]+))?$/' => '[commands][ {namespace:[a-zA-Z0-9]+}]',
    '/^console:cache$/' => 'console:cache',
    '/^console:commandsForBash valkyrja(?: ([a-zA-Z0-9\\:]+))?$/' => 'console:commandsForBash valkyrja[ {commandTyped:[a-zA-Z0-9\\:]+}]',
    '/^container:cache$/' => 'container:cache',
    '/^events:cache$/' => 'events:cache',
    '/^routes:cache$/' => 'routes:cache',
    '/^routes:list$/' => 'routes:list',
  ),
  'namedCommands' => 
  array (
  ),
  'provided' => 
  array (
    'config:cache' => 'Valkyrja\\Config\\Commands\\ConfigCache',
    'cache:all[ {sync:-s|--sync}]' => 'Valkyrja\\Console\\Commands\\CacheAllCommand',
    '[commands][ {namespace:[a-zA-Z0-9]+}]' => 'Valkyrja\\Console\\Commands\\ConsoleCommands',
    'console:cache' => 'Valkyrja\\Console\\Commands\\ConsoleCache',
    'console:commandsForBash valkyrja[ {commandTyped:[a-zA-Z0-9\\:]+}]' => 'Valkyrja\\Console\\Commands\\ConsoleCommandsForBash',
    'container:cache' => 'Valkyrja\\Container\\Commands\\ContainerCache',
    'events:cache' => 'Valkyrja\\Events\\Commands\\EventsCache',
    'routes:cache' => 'Valkyrja\\Routing\\Commands\\RoutesCacheCommand',
    'routes:list' => 'Valkyrja\\Routing\\Commands\\RoutesListCommand',
  ),
);