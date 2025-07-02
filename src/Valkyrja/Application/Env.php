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

namespace Valkyrja\Application;

use League\Flysystem\FilesystemAdapter as FlysystemAdapter;
use Twig\Extension\ExtensionInterface as TwigExtensionInterface;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Support\Component;
use Valkyrja\Asset\Adapter\Contract\Adapter as AssetAdapter;
use Valkyrja\Auth\Adapter\Contract\Adapter as AuthAdapter;
use Valkyrja\Auth\Entity\Contract\User as AuthUser;
use Valkyrja\Auth\Gate\Contract\Gate as AuthGate;
use Valkyrja\Auth\Policy\Contract\Policy as AuthPolicy;
use Valkyrja\Auth\Repository\Contract\Repository as AuthRepository;
use Valkyrja\Broadcast\Adapter\Contract\Adapter as BroadcastAdapter;
use Valkyrja\Broadcast\Config\Configurations as BroadcastConfigurations;
use Valkyrja\Broadcast\Config\MessageConfigurations as BroadcastMessageConfigurations;
use Valkyrja\Broadcast\Driver\Contract\Driver as BroadcastDriver;
use Valkyrja\Broadcast\Message\Contract\Message as BroadcastMessage;
use Valkyrja\Cache\Adapter\Contract\Adapter as CacheAdapter;
use Valkyrja\Cache\Config\Configurations as CacheConfigurations;
use Valkyrja\Cache\Driver\Contract\Driver as CacheDriver;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Filesystem\Adapter\Contract\Adapter as FilesystemAdapter;
use Valkyrja\Filesystem\Config\Configurations as FilesystemConfigurations;
use Valkyrja\Filesystem\Driver\Contract\Driver as FilesystemDriver;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware as HttpRequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware as HttpRouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware as HttpRouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware as HttpRouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware as HttpSendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware as HttpTerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware as HttpThrowableCaughtMiddleware;
use Valkyrja\Jwt\Adapter\Contract\Adapter as JwtAdapter;
use Valkyrja\Jwt\Config\Configurations as JwtConfiguration;
use Valkyrja\Jwt\Driver\Contract\Driver as JwtDriver;
use Valkyrja\Mail\Adapter\Contract\Adapter as MailAdapter;
use Valkyrja\Mail\Config\Configurations as MailConfigurations;
use Valkyrja\Mail\Config\MessageConfigurations as MailMessageConfigurations;
use Valkyrja\Mail\Driver\Contract\Driver as MailDriver;
use Valkyrja\Mail\Message\Contract\Message as MailMessage;
use Valkyrja\Orm\Adapter\Contract\Adapter as OrmAdapter;
use Valkyrja\Orm\Config\Connections as OrmConnections;
use Valkyrja\Orm\Driver\Contract\Driver as OrmDriver;
use Valkyrja\Orm\Pdo\Pdo as OrmPdo;
use Valkyrja\Orm\Persister\Contract\Persister as OrmPersister;
use Valkyrja\Orm\Query\Contract\Query as OrmQuery;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder as OrmQueryBuilder;
use Valkyrja\Orm\Repository\Contract\Repository as OrmRepository;
use Valkyrja\Orm\Retriever\Contract\Retriever as OrmRetriever;
use Valkyrja\Sms\Adapter\Contract\Adapter as SmsAdapter;
use Valkyrja\Sms\Config\Configurations as SmsConfigurations;
use Valkyrja\Sms\Config\MessageConfiguration as SmsMessageConfiguration;
use Valkyrja\Sms\Driver\Contract\Driver as SmsDriver;
use Valkyrja\View\Config\Configurations as ViewConfigurations;
use Valkyrja\View\Engine\Contract\Engine as ViewEngine;

/**
 * Class Env.
 *
 * @author Melech Mizrachi
 *
 * @see    https://psalm.dev/r/eb18b4d7ae This one is fine
 * @see    https://psalm.dev/r/36fd31ac0e This on breaks, the moment we use an annotation?!!?!?
 */
class Env
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var non-empty-string */
    public const string APP_ENV = 'local';
    /** @var bool */
    public const bool APP_DEBUG_MODE = true;
    /** @var non-empty-string */
    public const string APP_URL = 'localhost';
    /** @var non-empty-string */
    public const string APP_TIMEZONE = 'UTC';
    /** @var non-empty-string */
    public const string APP_VERSION = Application::VERSION;
    /** @var non-empty-string */
    public const string APP_KEY = 'some_secret_app_key';
    /** @var class-string<Component>[] */
    public const array APP_COMPONENTS = [
        ComponentClass::API,
        ComponentClass::ASSET,
        ComponentClass::AUTH,
        ComponentClass::BROADCAST,
        ComponentClass::CACHE,
        ComponentClass::CRYPT,
        ComponentClass::FILESYSTEM,
        ComponentClass::JWT,
        ComponentClass::LOG,
        ComponentClass::MAIL,
        ComponentClass::NOTIFICATION,
        ComponentClass::ORM,
        ComponentClass::SESSION,
        ComponentClass::SMS,
        ComponentClass::VIEW,
    ];
    /** @var string */
    public const string APP_DIR = __DIR__ . '/..';
    /** @var string */
    public const string APP_CACHE_FILE_PATH = __DIR__ . '/../storage/framework/cache/cache.php';

    /************************************************************
     *
     * Asset component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null ASSET_DEFAULT_BUNDLE = null;
    /** @var class-string<AssetAdapter>|null */
    public const string|null ASSET_DFAULT_ADAPTER_CLASS = null;
    /** @var string|null */
    public const string|null ASSET_DFAULT_HOST = null;
    /** @var string|null */
    public const string|null ASSET_DFAULT_PATH = null;
    /** @var string|null */
    public const string|null ASSET_DFAULT_MANIFEST = null;

    /************************************************************
     *
     * Auth component env variables.
     *
     ************************************************************/

    /** @var class-string<AuthAdapter>|null */
    public const string|null AUTH_DEFAULT_ADAPTER = null;
    /** @var class-string<AuthUser>|null */
    public const string|null AUTH_DEFAULT_USER_ENTITY = null;
    /** @var class-string<AuthRepository>|null */
    public const string|null AUTH_DEFAULT_REPOSITORY = null;
    /** @var class-string<AuthGate>|null */
    public const string|null AUTH_DEFAULT_GATE = null;
    /** @var class-string<AuthPolicy>|null */
    public const string|null AUTH_DEFAULT_POLICY = null;
    /** @var bool|null */
    public const bool|null AUTH_SHOULD_ALWAYS_AUTHENTICATE = null;
    /** @var bool|null */
    public const bool|null AUTH_SHOULD_KEEP_USER_FRESH = null;
    /** @var bool|null */
    public const bool|null AUTH_SHOULD_USE_SESSION = null;
    /** @var string|null */
    public const string|null AUTH_AUTHENTICATE_ROUTE = null;
    /** @var string|null */
    public const string|null AUTH_AUTHENTICATE_URL = null;
    /** @var string|null */
    public const string|null AUTH_NOT_AUTHENTICATED_ROUTE = null;
    /** @var string|null */
    public const string|null AUTH_NOT_AUTHENTICATED_URL = null;
    /** @var string|null */
    public const string|null AUTH_PASSWORD_CONFIRM_ROUTE = null;
    /** @var int|null */
    public const int|null AUTH_PASSWORD_TIMEOUT = null;

    /************************************************************
     *
     * Broadcast component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null BROADCAST_DEFAULT_CONFIGURATION = null;
    /** @var callable():BroadcastConfigurations|null */
    public const array|null BROADCAST_CONFIGURATIONS = null;
    /** @var string|null */
    public const string|null BROADCAST_DEFAULT_MESSAGE_CONFIGURATION = null;
    /** @var callable():BroadcastMessageConfigurations|null */
    public const array|null BROADCAST_MESSAGE_CONFIGURATIONS = null;
    /** @var string|null */
    public const string|null BROADCAST_DEFAULT_MESSAGE_CHANNEL = null;
    /** @var class-string<BroadcastMessage>|null */
    public const string|null BROADCAST_DEFAULT_MESSAGE_CLASS = null;
    /** @var class-string<BroadcastAdapter>|null */
    public const string|null BROADCAST_PUSHER_ADAPTER_CLASS = null;
    /** @var class-string<BroadcastDriver>|null */
    public const string|null BROADCAST_PUSHER_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null BROADCAST_PUSHER_KEY = null;
    /** @var string|null */
    public const string|null BROADCAST_PUSHER_SECRET = null;
    /** @var string|null */
    public const string|null BROADCAST_PUSHER_ID = null;
    /** @var string|null */
    public const string|null BROADCAST_PUSHER_CLUSTER = null;
    /** @var bool|null */
    public const bool|null BROADCAST_PUSHER_USE_TLS = null;
    /** @var class-string<BroadcastAdapter>|null */
    public const string|null BROADCAST_LOG_ADAPTER_CLASS = null;
    /** @var class-string<BroadcastDriver>|null */
    public const string|null BROADCAST_LOG_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null BROADCAST_LOG_LOG_NAME = null;
    /** @var class-string<BroadcastAdapter>|null */
    public const string|null BROADCAST_NULL_ADAPTER_CLASS = null;
    /** @var class-string<BroadcastDriver>|null */
    public const string|null BROADCAST_NULL_DRIVER_CLASS = null;

    /************************************************************
     *
     * Cache component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null CACHE_DEFAULT_CONFIGURATION = null;
    /** @var callable():CacheConfigurations|null */
    public const array|null CACHE_CONFIGURATIONS = null;
    /** @var class-string<CacheAdapter>|null */
    public const string|null CACHE_REDIS_ADAPTER_CLASS = null;
    /** @var class-string<CacheDriver>|null */
    public const string|null CACHE_REDIS_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null CACHE_REDIS_HOST = null;
    /** @var int|null */
    public const int|null CACHE_REDIS_PORT = null;
    /** @var string|null */
    public const string|null CACHE_REDIS_PREFIX = null;
    /** @var class-string<CacheAdapter>|null */
    public const string|null CACHE_LOG_ADAPTER_CLASS = null;
    /** @var class-string<CacheDriver>|null */
    public const string|null CACHE_LOG_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null CACHE_LOG_PREFIX = null;
    /** @var string|null */
    public const string|null CACHE_LOG_LOGGER = null;
    /** @var class-string<CacheAdapter>|null */
    public const string|null CACHE_NULL_ADAPTER_CLASS = null;
    /** @var class-string<CacheDriver>|null */
    public const string|null CACHE_NULL_DRIVER_CLASS = null;

    /************************************************************
     *
     * Cli Interaction component env variables.
     *
     ************************************************************/

    /** @var bool */
    public const bool CLI_INTERACTION_IS_QUIET = false;
    /** @var bool */
    public const bool CLI_INTERACTION_IS_INTERACTIVE = true;
    /** @var bool */
    public const bool CLI_INTERACTION_IS_SILENT = false;

    /************************************************************
     *
     * Cli Middleware component env variables.
     *
     ************************************************************/

    /** @var class-string<InputReceivedMiddleware>[] */
    public const array CLI_MIDDLEWARE_INPUT_RECEIVED = [];
    /** @var class-string<CommandMatchedMiddleware>[] */
    public const array CLI_MIDDLEWARE_COMMAND_MATCHED = [];
    /** @var class-string<CommandNotMatchedMiddleware>[] */
    public const array CLI_MIDDLEWARE_COMMAND_NOT_MATCHED = [];
    /** @var class-string<CommandDispatchedMiddleware>[] */
    public const array CLI_MIDDLEWARE_COMMAND_DISPATCHED = [];
    /** @var class-string<ThrowableCaughtMiddleware>[] */
    public const array CLI_MIDDLEWARE_THROWABLE_CAUGHT = [];
    /** @var class-string<ExitedMiddleware>[] */
    public const array CLI_MIDDLEWARE_EXITED = [];

    /************************************************************
     *
     * Filesystem component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null FILESYSTEM_DEFAULT_CONFIGURATION = null;
    /** @var callable():FilesystemConfigurations|null */
    public const array|null FILESYSTEM_CONFIGURATIONS = null;
    /** @var class-string<FilesystemAdapter>|null */
    public const string|null FILESYSTEM_FLYSYSTEM_LOCAL_ADAPTER_CLASS = null;
    /** @var class-string<FilesystemDriver>|null */
    public const string|null FILESYSTEM_FLYSYSTEM_LOCAL_DRIVER_CLASS = null;
    /** @var class-string<FlysystemAdapter>|null */
    public const string|null FILESYSTEM_FLYSYSTEM_LOCAL_FLYSYSTEM_ADAPTER = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_LOCAL_DIR = null;
    /** @var class-string<FilesystemAdapter>|null */
    public const string|null FILESYSTEM_IN_MEMORY_ADAPTER_CLASS = null;
    /** @var class-string<FilesystemDriver>|null */
    public const string|null FILESYSTEM_IN_MEMORY_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null FILESYSTEM_IN_MEMORY_DIR = null;
    /** @var class-string<FilesystemAdapter>|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_ADAPTER_CLASS = null;
    /** @var class-string<FilesystemDriver>|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_DRIVER_CLASS = null;
    /** @var class-string<FlysystemAdapter>|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_FLYSYSTEM_ADAPTER = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_KEY = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_SECRET = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_REGION = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_VERSION = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_BUCKET = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_PREFIX = null;
    /** @var array<string, mixed>|null */
    public const array|null FILESYSTEM_FLYSYSTEM_S3_OPTIONS = null;
    /** @var class-string<FilesystemAdapter>|null */
    public const string|null FILESYSTEM_NULL_ADAPTER_CLASS = null;
    /** @var class-string<FilesystemDriver>|null */
    public const string|null FILESYSTEM_NULL_DRIVER_CLASS = null;

    /************************************************************
     *
     * Http Middleware component env variables.
     *
     ************************************************************/

    /** @var class-string<HttpRequestReceivedMiddleware>[] */
    public const array HTTP_MIDDLEWARE_REQUEST_RECEIVED = [];
    /** @var class-string<HttpRouteDispatchedMiddleware>[] */
    public const array HTTP_MIDDLEWARE_ROUTE_DISPATCHED = [];
    /** @var class-string<HttpThrowableCaughtMiddleware>[] */
    public const array HTTP_MIDDLEWARE_THROWABLE_CAUGHT = [];
    /** @var class-string<HttpRouteMatchedMiddleware>[] */
    public const array HTTP_MIDDLEWARE_ROUTE_MATCHED = [];
    /** @var class-string<HttpRouteNotMatchedMiddleware>[] */
    public const array HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED = [];
    /** @var class-string<HttpSendingResponseMiddleware>[] */
    public const array HTTP_MIDDLEWARE_SENDING_RESPONSE = [];
    /** @var class-string<HttpTerminatedMiddleware>[] */
    public const array HTTP_MIDDLEWARE_TERMINATED = [];

    /************************************************************
     *
     * Jwt component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null JWT_DEFAULT_CONFIGURATION = null;
    /** @var callable():JwtConfiguration|null */
    public const array|null JWT_CONFIGURATIONS = null;
    /** @var class-string<JwtAdapter>|null */
    public const string|null JWT_HS_ADAPTER_CLASS = null;
    /** @var class-string<JwtDriver>|null */
    public const string|null JWT_HS_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null JWT_HS_ALGORITHM = null;
    /** @var string|null */
    public const string|null JWT_HS_KEY = null;
    /** @var string|null */
    public const string|null JWT_HS_DRIVER = null;
    /** @var class-string<JwtAdapter>|null */
    public const string|null JWT_RS_ADAPTER_CLASS = null;
    /** @var class-string<JwtDriver>|null */
    public const string|null JWT_RS_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null JWT_RS_ALGORITHM = null;
    /** @var string|null */
    public const string|null JWT_RS_PRIVATE_KEY = null;
    /** @var string|null */
    public const string|null JWT_RS_PUBLIC_KEY = null;
    /** @var string|null */
    public const string|null JWT_RS_KEY_PATH = null;
    /** @var string|null */
    public const string|null JWT_RS_PASSPHRASE = null;
    /** @var class-string<JwtAdapter>|null */
    public const string|null JWT_EDDSA_ADAPTER_CLASS = null;
    /** @var class-string<JwtDriver>|null */
    public const string|null JWT_EDDSA_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null JWT_EDDSA_ALGORITHM = null;
    /** @var string|null */
    public const string|null JWT_EDDSA_PRIVATE_KEY = null;
    /** @var string|null */
    public const string|null JWT_EDDSA_PUBLIC_KEY = null;
    /** @var class-string<JwtAdapter>|null */
    public const string|null JWT_NULL_ADAPTER_CLASS = null;
    /** @var class-string<JwtDriver>|null */
    public const string|null JWT_NULL_DRIVER_CLASS = null;

    /************************************************************
     *
     * Mail component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null MAIL_DEFAULT_CONFIGURATION = null;
    /** @var callable():MailConfigurations|null */
    public const array|null MAIL_CONFIGURATIONS = null;
    /** @var string|null */
    public const string|null MAIL_DEFAULT_MESSAGE_CONFIGURATION = null;
    /** @var callable():MailMessageConfigurations|null */
    public const array|null MAIL_MESSAGE_CONFIGURATIONS = null;
    /** @var string|null */
    public const string|null MAIL_DEFAULT_MESSAGE_FROM = null;
    /** @var class-string<MailMessage>|null */
    public const string|null MAIL_DEFAULT_MESSAGE_CLASS = null;
    /** @var class-string<MailAdapter>|null */
    public const string|null MAIL_MAILGUN_ADAPTER_CLASS = null;
    /** @var class-string<MailDriver>|null */
    public const string|null MAIL_MAILGUN_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null MAIL_MAILGUN_API_KEY = null;
    /** @var string|null */
    public const string|null MAIL_MAILGUN_DOMAIN = null;
    /** @var class-string<MailAdapter>|null */
    public const string|null MAIL_PHP_MAILER_ADAPTER_CLASS = null;
    /** @var class-string<MailDriver>|null */
    public const string|null MAIL_PHP_MAILER_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null MAIL_PHP_MAILER_HOST = null;
    /** @var int|null */
    public const int|null MAIL_PHP_MAILER_PORT = null;
    /** @var string|null */
    public const string|null MAIL_PHP_MAILER_USERNAME = null;
    /** @var string|null */
    public const string|null MAIL_PHP_MAILER_PASSWORD = null;
    /** @var string|null */
    public const string|null MAIL_PHP_MAILER_ENCRYPTION = null;
    /** @var string|null */
    public const string|null MAIL_LOG_LOGGER = null;
    /** @var class-string<MailAdapter>|null */
    public const string|null MAIL_LOG_ADAPTER_CLASS = null;
    /** @var class-string<MailDriver>|null */
    public const string|null MAIL_LOG_DRIVER_CLASS = null;
    /** @var class-string<MailAdapter>|null */
    public const string|null MAIL_NULL_ADAPTER_CLASS = null;
    /** @var class-string<MailDriver>|null */
    public const string|null MAIL_NULL_DRIVER_CLASS = null;

    /************************************************************
     *
     * Orm component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null ORM_DEFAULT_CONNECTION = null;
    /** @var callable():OrmConnections|null */
    public const array|null ORM_CONNECTIONS = null;
    /** @var string|null */
    public const string|null ORM_MIGRATIONS = null;
    /** @var class-string<OrmAdapter>|null */
    public const string|null ORM_PGSQL_ADAPTER_CLASS = null;
    /** @var class-string<OrmDriver>|null */
    public const string|null ORM_PGSQL_DRIVER_CLASS = null;
    /** @var class-string<OrmRepository>|null */
    public const string|null ORM_PGSQL_REPOSITORY_CLASS = null;
    /** @var class-string<OrmQuery>|null */
    public const string|null ORM_PGSQL_QUERY_CLASS = null;
    /** @var class-string<OrmQueryBuilder>|null */
    public const string|null ORM_PGSQL_QUERY_BUILDER_CLASS = null;
    /** @var class-string<OrmPersister>|null */
    public const string|null ORM_PGSQL_PERSISTER_CLASS = null;
    /** @var class-string<OrmRetriever>|null */
    public const string|null ORM_PGSQL_RETRIEVER_CLASS = null;
    /** @var class-string<OrmPdo>|null */
    public const string|null ORM_PGSQL_PDO_CLASS = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_PDO_DRIVER = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_HOST = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_PORT = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_DB = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_USER = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_PASSWORD = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_CHARSET = null;
    /** @var array<int, int|bool>|null */
    public const array|null ORM_PGSQL_OPTIONS = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_SCHEMA = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_SSL_MODE = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_SSL_CERT = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_KEY = null;
    /** @var string|null */
    public const string|null ORM_PGSQL_ROOT_KEY = null;
    /** @var class-string<OrmAdapter>|null */
    public const string|null ORM_MYSQL_ADAPTER_CLASS = null;
    /** @var class-string<OrmDriver>|null */
    public const string|null ORM_MYSQL_DRIVER_CLASS = null;
    /** @var class-string<OrmRepository>|null */
    public const string|null ORM_MYSQL_REPOSITORY_CLASS = null;
    /** @var class-string<OrmQuery>|null */
    public const string|null ORM_MYSQL_QUERY_CLASS = null;
    /** @var class-string<OrmQueryBuilder>|null */
    public const string|null ORM_MYSQL_QUERY_BUILDER_CLASS = null;
    /** @var class-string<OrmPersister>|null */
    public const string|null ORM_MYSQL_PERSISTER_CLASS = null;
    /** @var class-string<OrmRetriever>|null */
    public const string|null ORM_MYSQL_RETRIEVER_CLASS = null;
    /** @var class-string<OrmPdo>|null */
    public const string|null ORM_MYSQL_PDO_CLASS = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_PDO_DRIVER = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_HOST = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_PORT = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_DB = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_USER = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_PASSWORD = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_CHARSET = null;
    /** @var array<int, int|bool>|null */
    public const array|null ORM_MYSQL_OPTIONS = null;
    /** @var bool|null */
    public const bool|null ORM_MYSQL_STRICT = null;
    /** @var string|null */
    public const string|null ORM_MYSQL_ENGINE = null;

    /************************************************************
     *
     * Session component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null SESSION_PHP_ID = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_PHP_NAME = null;
    /** @var non-empty-string */
    public const string SESSION_COOKIE_PARAM_PATH = '/';
    /** @var non-empty-string|null */
    public const string|null SESSION_COOKIE_PARAM_DOMAIN = null;
    /** @var int */
    public const int SESSION_COOKIE_PARAM_LIFETIME = 0;
    /** @var bool */
    public const bool SESSION_COOKIE_PARAM_SECURE = false;
    /** @var bool */
    public const bool SESSION_COOKIE_PARAM_HTTP_ONLY = false;
    /** @var SameSite */
    public const SameSite SESSION_COOKIE_PARAM_SAME_SITE = SameSite::NONE;

    /************************************************************
     *
     * SMS component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null SMS_DEFAULT_CONFIGURATION = null;
    /** @var callable():SmsConfigurations|null */
    public const array|null SMS_CONFIGURATIONS = null;
    /** @var string|null */
    public const string|null SMS_DEFAULT_MESSAGE_CONFIGURATION = null;
    /** @var callable():SmsMessageConfiguration|null */
    public const array|null SMS_MESSAGE_CONFIGURATIONS = null;
    /** @var class-string<SmsAdapter>|null */
    public const string|null SMS_NULL_ADAPTER_CLASS = null;
    /** @var class-string<SmsDriver>|null */
    public const string|null SMS_NULL_DRIVER_CLASS = null;
    /** @var class-string<SmsAdapter>|null */
    public const string|null SMS_LOG_ADAPTER_CLASS = null;
    /** @var class-string<SmsDriver>|null */
    public const string|null SMS_LOG_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null SMS_LOG_LOG_NAME = null;
    /** @var string|null */
    public const string|null SMS_VONAGE_KEY = null;
    /** @var string|null */
    public const string|null SMS_VONAGE_SECRET = null;
    /** @var class-string<SmsAdapter>|null */
    public const string|null SMS_VONAGE_ADAPTER_CLASS = null;
    /** @var class-string<SmsDriver>|null */
    public const string|null SMS_VONAGE_DRIVER_CLASS = null;

    /************************************************************
     *
     * View component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null VIEW_DEFAULT_CONFIGURATION = null;
    /** @var callable():ViewConfigurations|null */
    public const array|null VIEW_CONFIGURATIONS = null;
    /** @var class-string<ViewEngine>|null */
    public const string|null VIEW_ORKA_ENGINE = null;
    /** @var string|null */
    public const string|null VIEW_ORKA_FILE_EXTENSION = null;
    /** @var string|null */
    public const string|null VIEW_ORKA_DIR = null;
    /** @var array<string, string>|null */
    public const array|null VIEW_ORKA_PATHS = null;
    /** @var class-string<ViewEngine>|null */
    public const string|null VIEW_PHP_ENGINE = null;
    /** @var string|null */
    public const string|null VIEW_PHP_FILE_EXTENSION = null;
    /** @var string|null */
    public const string|null VIEW_PHP_DIR = null;
    /** @var array<string, string>|null */
    public const array|null VIEW_PHP_PATHS = null;
    /** @var class-string<ViewEngine>|null */
    public const string|null VIEW_TWIG_ENGINE = null;
    /** @var string|null */
    public const string|null VIEW_TWIG_FILE_EXTENSION = null;
    /** @var string|null */
    public const string|null VIEW_TWIG_DIR = null;
    /** @var array<string, string>|null */
    public const array|null VIEW_TWIG_PATHS = null;
    /** @var class-string<TwigExtensionInterface>[]|null */
    public const array|null VIEW_TWIG_EXTENSIONS = null;
    /** @var string|null */
    public const string|null VIEW_TWIG_COMPILED_DIR = null;
}
