<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Constant;

/**
 * Constant ConfigKeyPart.
 *
 * @author Melech Mizrachi
 */
final class ConfigKeyPart
{
    public const SEP = '.';

    public const CONFIG      = 'config';
    public const CACHE       = 'cache';
    public const API         = 'api';
    public const APP         = 'app';
    public const ANNOTATIONS = 'annotations';
    public const AUTH        = 'auth';
    public const CONSOLE     = 'console';
    public const CONTAINER   = 'container';
    public const CRYPT       = 'crypt';
    public const DISPATCHER  = 'dispatcher';
    public const EVENTS      = 'events';
    public const FILESYSTEM  = 'filesystem';
    public const LOG         = 'log';
    public const MAIL        = 'mail';
    public const ORM         = 'orm';
    public const PATH        = 'path';
    public const ROUTING     = 'routing';
    public const SMS         = 'sms';
    public const SESSION     = 'session';
    public const STORAGE     = 'storage';
    public const VALIDATION  = 'validation';
    public const VIEW        = 'view';
    public const TWIG        = 'twig';

    public const ENV                    = 'env';
    public const DEBUG                  = 'debug';
    public const URL                    = 'url';
    public const TIMEZONE               = 'timezone';
    public const VERSION                = 'version';
    public const KEY                    = 'key';
    public const PRIVATE_KEY            = 'privateKey';
    public const PUBLIC_KEY             = 'publicKey';
    public const KEY_PATH               = 'keyPath';
    public const HTTP_EXCEPTION         = 'httpException';
    public const ERROR_HANDLER          = 'errorHandler';
    public const HTTP_KERNEL            = 'httpKernel';
    public const PATTERNS               = 'patterns';
    public const ENABLED                = 'enabled';
    public const MAP                    = 'map';
    public const NAME                   = 'name';
    public const FROM_ADDRESS           = 'fromAddress';
    public const FROM_NAME              = 'fromName';
    public const DEFAULT                = 'default';
    public const PROVIDERS              = 'providers';
    public const PROVIDED               = 'provided';
    public const COLLECTION             = 'collection';
    public const CONFIGURATION          = 'configuration';
    public const CONFIGURATIONS         = 'configurations';
    public const ALIASES                = 'aliases';
    public const DEV_PROVIDERS          = 'devProviders';
    public const FILE_PATH              = 'filePath';
    public const CACHE_DIR              = 'cacheDir';
    public const CACHE_FILE_PATH        = 'cacheFilePath';
    public const USE_CACHE              = 'useCache';
    public const USE_ANNOTATIONS        = 'useAnnotations';
    public const USE_ATTRIBUTES         = 'useAttributes';
    public const QUIET                  = 'quiet';
    public const LISTENERS              = 'listeners';
    public const HANDLERS               = 'handlers';
    public const SERVICES               = 'services';
    public const CONTEXT_SERVICES       = 'contextServices';
    public const CONNECTION             = 'connection';
    public const CONNECTIONS            = 'connections';
    public const MIGRATIONS             = 'migrations';
    public const RULE                   = 'rule';
    public const RULES                  = 'rules';
    public const RULES_MAP              = 'rulesMap';
    public const MYSQL                  = 'mysql';
    public const PGSQL                  = 'pgsql';
    public const SQLSRV                 = 'sqlsrv';
    public const DRIVER                 = 'driver';
    public const DRIVERS                = 'drivers';
    public const HOST                   = 'host';
    public const PORT                   = 'port';
    public const DB                     = 'db';
    public const USER                   = 'user';
    public const USERNAME               = 'username';
    public const PASSWORD               = 'password';
    public const PASSPHRASE             = 'passphrase';
    public const MESSAGE                = 'message';
    public const DEFAULT_MESSAGE        = 'defaultMessage';
    public const MESSAGE_ADAPTERS       = 'messageAdapters';
    public const MESSAGES               = 'messages';
    public const PHP_MAILER             = 'phpMailer';
    public const MAILGUN                = 'mailgun';
    public const NEXMO                  = 'nexmo';
    public const UNIX_SOCKET            = 'unix_socket';
    public const CHARSET                = 'charset';
    public const COLLATION              = 'collation';
    public const PREFIX                 = 'prefix';
    public const STRICT                 = 'strict';
    public const ENGINES                = 'engines';
    public const ENGINE                 = 'engine';
    public const SCHEMA                 = 'schema';
    public const SSL_MODE               = 'sslmode';
    public const SSL_CERT               = 'sslcert';
    public const SSL_KEY                = 'sslkey';
    public const SSL_ROOT_CERT          = 'sslrootcert';
    public const CLASSES                = 'classes';
    public const STORE                  = 'store';
    public const STORES                 = 'stores';
    public const DISKS                  = 'disks';
    public const DISK                   = 'disk';
    public const ADAPTERS               = 'adapters';
    public const ADAPTER                = 'adapter';
    public const RETRIEVER              = 'retriever';
    public const PERSISTER              = 'persister';
    public const QUERY                  = 'query';
    public const QUERY_BUILDER          = 'queryBuilder';
    public const USER_ENTITY            = 'userEntity';
    public const REPOSITORY             = 'repository';
    public const GATE                   = 'gate';
    public const GATES                  = 'gates';
    public const POLICY                 = 'policy';
    public const POLICIES               = 'policies';
    public const ALWAYS_AUTHENTICATE    = 'alwaysAuthenticate';
    public const KEEP_USER_FRESH        = 'keepUserFresh';
    public const AUTHENTICATE_ROUTE     = 'authenticateRoute';
    public const AUTHENTICATE_URL       = 'authenticateUrl';
    public const NOT_AUTHENTICATE_ROUTE = 'notAuthenticateRoute';
    public const NOT_AUTHENTICATE_URL   = 'notAuthenticateUrl';
    public const PASSWORD_CONFIRM_ROUTE = 'passwordConfirmRoute';
    public const USE_SESSION            = 'useSession';
    public const PDO                    = 'pdo';
    public const PDO_CACHE              = 'pdoCache';
    public const PHP                    = 'php';
    public const FLYSYSTEM              = 'flysystem';
    public const FLYSYSTEM_ADAPTER      = 'flysystemAdapter';
    public const LOCAL                  = 'local';
    public const IN_MEMORY              = 'inMemory';
    public const S3                     = 's3';
    public const DIR                    = 'dir';
    public const COMPILED_DIR           = 'compiledDir';
    public const SECRET                 = 'secret';
    public const REGION                 = 'region';
    public const BUCKET                 = 'bucket';
    public const OPTIONS                = 'options';
    public const ADDRESS                = 'address';
    public const ENCRYPTION             = 'encryption';
    public const CONTROLLERS            = 'controllers';
    public const USE_TRAILING_SLASH     = 'useTrailingSlash';
    public const USE_ABSOLUTE_URLS      = 'useAbsoluteUrls';
    public const MIDDLEWARE             = 'middleware';
    public const MIDDLEWARE_GROUPS      = 'middlewareGroups';
    public const ID                     = 'id';
    public const PATHS                  = 'paths';
    public const REGEX                  = 'regex';
    public const COMMANDS               = 'commands';
    public const NAMED_COMMANDS         = 'namedCommands';
    public const VALHALLA               = 'valhalla';
    public const REDIS                  = 'redis';
    public const JSON_MODEL             = 'jsonModel';
    public const JSON_DATA_MODEL        = 'jsonDataModel';
    public const SODIUM                 = 'sodium';
    public const GUZZLE                 = 'guzzle';
    public const PSR                    = 'psr';
    public const PUSHER                 = 'pusher';
    public const NULL                   = 'null';
    public const COOKIE                 = 'cookie';
    public const CLUSTER                = 'cluster';
    public const USE_TLS                = 'useTls';
    public const EXTENSIONS             = 'extensions';
    public const FILE_EXTENSION         = 'fileExtension';
    public const ORKA                   = 'orka';
    public const NOTIFICATIONS          = 'notifications';
    public const DOMAIN                 = 'domain';
    public const API_KEY                = 'apiKey';
    public const CRYPTS                 = 'crypts';
    public const SESSIONS               = 'sessions';
    public const LOGGER                 = 'logger';
    public const LOGGERS                = 'loggers';
    public const CLIENT                 = 'client';
    public const CLIENTS                = 'clients';
    public const MAILER                 = 'mailer';
    public const MAILERS                = 'mailers';
    public const MESSENGER              = 'messenger';
    public const MESSENGERS             = 'messengers';
    public const BROADCASTER            = 'broadcaster';
    public const BROADCASTERS           = 'broadcasters';
    public const COOKIE_PARAMS          = 'cookieParams';
    public const BUNDLE                 = 'bundle';
    public const BUNDLES                = 'bundles';
    public const MANIFEST               = 'manifest';
    public const ALGO                   = 'algo';
    public const ALGOS                  = 'algos';
}
