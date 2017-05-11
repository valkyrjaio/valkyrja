<?php return [
    'routes'        =>
        [
            '/property'                  =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/property',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'HEAD',
                        ],
                    'regex'               => null,
                    'params'              => null,
                    'segments'            => null,
                    'dynamic'             => false,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.property',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => 'propertyRouting',
                    'method'              => null,
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/test/property'             =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/test/property',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'HEAD',
                        ],
                    'regex'               => null,
                    'params'              => null,
                    'segments'            => null,
                    'dynamic'             => false,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.test.property',
                    'closure'             => null,
                    'dependencies'        => null,
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => 'propertyRouting',
                    'method'              => null,
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/'                          =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'HEAD',
                        ],
                    'regex'               => null,
                    'params'              => null,
                    'segments'            => null,
                    'dynamic'             => false,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.welcome',
                    'closure'             => null,
                    'dependencies'        =>
                        [
                        ],
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => null,
                    'method'              => 'welcome',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/test'                      =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/test',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'HEAD',
                        ],
                    'regex'               => null,
                    'params'              => null,
                    'segments'            => null,
                    'dynamic'             => false,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.test.welcome',
                    'closure'             => null,
                    'dependencies'        =>
                        [
                        ],
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => null,
                    'method'              => 'welcome',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/version'                   =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/version',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'POST',
                            2 => 'HEAD',
                        ],
                    'regex'               => null,
                    'params'              => null,
                    'segments'            => null,
                    'dynamic'             => false,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.version',
                    'closure'             => null,
                    'dependencies'        =>
                        [
                        ],
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => null,
                    'method'              => 'version',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/test/version'              =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/test/version',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'POST',
                            2 => 'HEAD',
                        ],
                    'regex'               => null,
                    'params'              => null,
                    'segments'            => null,
                    'dynamic'             => false,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.test.version',
                    'closure'             => null,
                    'dependencies'        =>
                        [
                        ],
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => null,
                    'method'              => 'version',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/home[/page/{id:num}]'      =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/home[/page/{id:num}]',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'HEAD',
                        ],
                    'regex'               => '/^\\/home(?:\\/page\\/(\\d+))?$/',
                    'params'              =>
                        [
                            'id' =>
                                [
                                    'replace' => '{id}',
                                    'regex'   => '(\\d+)',
                                ],
                        ],
                    'segments'            =>
                        [
                            0 => '/home',
                            1 => '/page/{id}',
                        ],
                    'dynamic'             => true,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.home',
                    'closure'             => null,
                    'dependencies'        =>
                        [
                            0 => 'Valkyrja\\Contracts\\Application',
                        ],
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => null,
                    'method'              => 'home',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
            '/test/home[/page/{id:num}]' =>
                Valkyrja\Routing\Route::__set_state([
                    'path'                => '/test/home[/page/{id:num}]',
                    'requestMethods'      =>
                        [
                            0 => 'GET',
                            1 => 'HEAD',
                        ],
                    'regex'               => '/^\\/test\\/home(?:\\/page\\/(\\d+))?$/',
                    'params'              =>
                        [
                            'id' =>
                                [
                                    'replace' => '{id}',
                                    'regex'   => '(\\d+)',
                                ],
                        ],
                    'segments'            =>
                        [
                            0 => '/test/home',
                            1 => '/page/{id}',
                        ],
                    'dynamic'             => true,
                    'secure'              => false,
                    'id'                  => null,
                    'name'                => 'home.test.home',
                    'closure'             => null,
                    'dependencies'        =>
                        [
                            0 => 'Valkyrja\\Contracts\\Application',
                        ],
                    'arguments'           => null,
                    'annotationType'      => 'Route',
                    'class'               => 'App\\Controllers\\HomeController',
                    'property'            => null,
                    'method'              => 'home',
                    'static'              => null,
                    'function'            => null,
                    'matches'             => null,
                    'annotationArguments' => null,
                ]),
        ],
    'staticRoutes'  =>
        [
            '/property'      => true,
            '/test/property' => true,
            '/'              => true,
            '/test'          => true,
            '/version'       => true,
            '/test/version'  => true,
        ],
    'dynamicRoutes' =>
        [
            '/^\\/home(?:\\/page\\/(\\d+))?$/'        => '/home[/page/{id:num}]',
            '/^\\/test\\/home(?:\\/page\\/(\\d+))?$/' => '/test/home[/page/{id:num}]',
        ],
    'namedRoutes'   =>
        [
            'home.property'      => '/property',
            'home.test.property' => '/test/property',
            'home.welcome'       => '/',
            'home.test.welcome'  => '/test',
            'home.version'       => '/version',
            'home.test.version'  => '/test/version',
            'home.home'          => '/home[/page/{id:num}]',
            'home.test.home'     => '/test/home[/page/{id:num}]',
        ],
];