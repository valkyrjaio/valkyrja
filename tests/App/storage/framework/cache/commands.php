<?php return [
    'commands'      =>
        [
            '[commands][ {namespace:[a-zA-Z0-9]+}]'                             =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => '[commands][ {namespace:[a-zA-Z0-9]+}]',
                    'regex'               => '/^(?:commands)?(?: ([a-zA-Z0-9]+))?$/',
                    'params'              =>
                        [
                            'namespace' =>
                                [
                                    'replace' => '{namespace}',
                                    'regex'   => '([a-zA-Z0-9]+)',
                                ],
                        ],
                    'segments'            =>
                        [
                            0 => '',
                            1 => 'commands',
                            2 => ' {namespace}',
                        ],
                    'description'         => 'List all the commands',
                    'id'                  => null,
                    'name'                => 'commands',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Console\\Commands\\ConsoleCommands',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'console:commandsForBash valkyrja[ {commandTyped:[a-zA-Z0-9\\:]+}]' =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'console:commandsForBash valkyrja[ {commandTyped:[a-zA-Z0-9\\:]+}]',
                    'regex'               => '/^console:commandsForBash valkyrja(?: ([a-zA-Z0-9\\:]+))?$/',
                    'params'              =>
                        [
                            'commandTyped' =>
                                [
                                    'replace' => '{commandTyped}',
                                    'regex'   => '([a-zA-Z0-9\\:]+)',
                                ],
                        ],
                    'segments'            =>
                        [
                            0 => 'console:commandsForBash valkyrja',
                            1 => ' {commandTyped}',
                        ],
                    'description'         => 'List all the commands for bash auto complete',
                    'id'                  => null,
                    'name'                => 'console:commandsForBash',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Console\\Commands\\ConsoleCommandsForBash',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'cache:all[ {sync:-s|--sync}]'                                      =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'cache:all[ {sync:-s|--sync}]',
                    'regex'               => '/^cache:all(?: (-s|--sync))?$/',
                    'params'              =>
                        [
                            'sync' =>
                                [
                                    'replace' => '{sync}',
                                    'regex'   => '(-s|--sync)',
                                ],
                        ],
                    'segments'            =>
                        [
                            0 => 'cache:all',
                            1 => ' {sync}',
                        ],
                    'description'         => 'Generate all caches and sync',
                    'id'                  => null,
                    'name'                => 'cache:all',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Console\\Commands\\CacheAllCommand',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'console:cache'                                                     =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'console:cache',
                    'regex'               => '/^console:cache$/',
                    'params'              =>
                        [
                        ],
                    'segments'            =>
                        [
                            0 => 'console:cache',
                        ],
                    'description'         => 'Generate the console cache',
                    'id'                  => null,
                    'name'                => 'console:cache',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Console\\Commands\\ConsoleCache',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'container:cache'                                                   =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'container:cache',
                    'regex'               => '/^container:cache$/',
                    'params'              =>
                        [
                        ],
                    'segments'            =>
                        [
                            0 => 'container:cache',
                        ],
                    'description'         => 'Generate the container cache',
                    'id'                  => null,
                    'name'                => 'container:cache',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Container\\Commands\\ContainerCache',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'events:cache'                                                      =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'events:cache',
                    'regex'               => '/^events:cache$/',
                    'params'              =>
                        [
                        ],
                    'segments'            =>
                        [
                            0 => 'events:cache',
                        ],
                    'description'         => 'Generate the events cache',
                    'id'                  => null,
                    'name'                => 'events:cache',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Events\\Commands\\EventsCache',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'routes:cache'                                                      =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'routes:cache',
                    'regex'               => '/^routes:cache$/',
                    'params'              =>
                        [
                        ],
                    'segments'            =>
                        [
                            0 => 'routes:cache',
                        ],
                    'description'         => 'Generate the routes cache',
                    'id'                  => null,
                    'name'                => 'routes:cache',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Routing\\Commands\\RoutesCacheCommand',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            'routes:list'                                                       =>
                Valkyrja\Console\Command::__set_state([
                    'path'                => 'routes:list',
                    'regex'               => '/^routes:list$/',
                    'params'              =>
                        [
                        ],
                    'segments'            =>
                        [
                            0 => 'routes:list',
                        ],
                    'description'         => 'List all routes',
                    'id'                  => null,
                    'name'                => 'routes:list',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => null,
                    'class'               => 'Valkyrja\\Routing\\Commands\\RoutesListCommand',
                    'property'            => null,
                    'method'              => 'run',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
        ],
    'namedCommands' =>
        [
            'commands'                => '[commands][ {namespace:[a-zA-Z0-9]+}]',
            'console:commandsForBash' => 'console:commandsForBash valkyrja[ {commandTyped:[a-zA-Z0-9\\:]+}]',
            'cache:all'               => 'cache:all[ {sync:-s|--sync}]',
            'console:cache'           => 'console:cache',
            'container:cache'         => 'container:cache',
            'events:cache'            => 'events:cache',
            'routes:cache'            => 'routes:cache',
            'routes:list'             => 'routes:list',
        ],
];