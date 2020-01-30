<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum ConfigKeyPart.
 *
 * @author Melech Mizrachi
 */
final class ConfigKeyPart extends Enum
{
    public const SEP = '.';

    public const CONFIG      = 'config';
    public const CACHE       = 'cache';
    public const APP         = 'app';
    public const ANNOTATIONS = 'annotations';
    public const CONSOLE     = 'console';
    public const CONTAINER   = 'container';
    public const CRYPT       = 'crypt';
    public const DB          = 'database';
    public const EVENTS      = 'events';
    public const FILESYSTEM  = 'filesystem';
    public const LOGGER      = 'logger';
    public const MAIL        = 'mail';
    public const ROUTING     = 'routing';
    public const SESSION     = 'session';
    public const STORAGE     = 'storage';
    public const VIEWS       = 'views';
    public const TWIG        = 'twig';

    public const ENV                         = 'env';
    public const DEBUG                       = 'debug';
    public const URL                         = 'url';
    public const TIMEZONE                    = 'timezone';
    public const VERSION                     = 'version';
    public const KEY                         = 'key';
    public const KEY_PATH                    = 'keyPath';
    public const HTTP_EXCEPTION_CLASS        = 'httpExceptionClass';
    public const DISPATCHER                  = 'dispatcher';
    public const EXCEPTION_HANDLER           = 'exceptionHandler';
    public const PATH_REGEX_MAP              = 'pathRegexMap';
    public const ENABLED                     = 'enabled';
    public const MAP                         = 'map';
    public const NAME                        = 'name';
    public const FROM                        = 'from';
    public const DEFAULT                     = 'default';
    public const PROVIDERS                   = 'providers';
    public const PROVIDED                    = 'provided';
    public const COLLECTION                  = 'collection';
    public const ALIASES                     = 'aliases';
    public const DEV_PROVIDERS               = 'devProviders';
    public const FILE_PATH                   = 'filePath';
    public const CACHE_DIR                   = 'cacheDir';
    public const CACHE_FILE_PATH             = 'cacheFilePath';
    public const USE_CACHE                   = 'useCache';
    public const USE_ANNOTATIONS             = 'useAnnotations';
    public const USE_ANNOTATIONS_EXCLUSIVELY = 'useAnnotationsExclusively';
    public const QUIET                       = 'quiet';
    public const HANDLERS                    = 'handlers';
    public const SERVICES                    = 'services';
    public const CONTEXT_SERVICES            = 'contextServices';
    public const CONNECTIONS                 = 'connections';
    public const MYSQL                       = 'mysql';
    public const PGSQL                       = 'pgsql';
    public const SQLSRV                      = 'sqlsrv';
    public const DRIVER                      = 'driver';
    public const HOST                        = 'host';
    public const PORT                        = 'port';
    public const USERNAME                    = 'username';
    public const PASSWORD                    = 'password';
    public const UNIX_SOCKET                 = 'unix_socket';
    public const CHARSET                     = 'charset';
    public const COLLATION                   = 'collation';
    public const PREFIX                      = 'prefix';
    public const STRICT                      = 'strict';
    public const ENGINE                      = 'engine';
    public const SCHEMA                      = 'schema';
    public const SSL_MODE                    = 'sslmode';
    public const CLASSES                     = 'classes';
    public const ADAPTERS                    = 'adapters';
    public const LOCAL                       = 'local';
    public const S3                          = 's3';
    public const DIR                         = 'dir';
    public const SECRET                      = 'secret';
    public const REGION                      = 'region';
    public const BUCKET                      = 'bucket';
    public const OPTIONS                     = 'option';
    public const ADDRESS                     = 'address';
    public const ENCRYPTION                  = 'encryption';
    public const CONTROLLERS                 = 'controllers';
    public const TRAILING_SLASH              = 'trailingSlash';
    public const USE_ABSOLUTE_URLS           = 'useAbsoluteUrls';
    public const MIDDLEWARE                  = 'middleware';
    public const MIDDLEWARE_GROUPS           = 'middlewareGroups';
    public const ID                          = 'id';
    public const PATHS                       = 'paths';
    public const REGEX                       = 'regex';
    public const COMMANDS                    = 'commands';
    public const NAMED_COMMANDS              = 'namedCommands';
    public const COMMAND                     = 'Command';
    public const LISTENER                    = 'Listener';
    public const ROUTE                       = 'Route';
    public const SERVICE                     = 'Service';
    public const SERVICE_ALIAS               = 'ServiceAlias';
    public const SERVICE_CONTEXT             = 'ServiceContext';
}
