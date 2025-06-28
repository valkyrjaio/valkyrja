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
use Valkyrja\Api\Model\Contract\Json as ApiJson;
use Valkyrja\Api\Model\Contract\JsonData as ApiJsonData;
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
use Valkyrja\Cli\Interaction\Config as CliInteractionConfig;
use Valkyrja\Cli\Middleware\Config as CliMiddlewareConfig;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Routing\Config as CliRoutingConfig;
use Valkyrja\Container\Contract\Service as ContainerService;
use Valkyrja\Container\Support\Provider as ContainerProvider;
use Valkyrja\Filesystem\Adapter\Contract\Adapter as FilesystemAdapter;
use Valkyrja\Filesystem\Config\Configurations as FilesystemConfigurations;
use Valkyrja\Filesystem\Driver\Contract\Driver as FilesystemDriver;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Middleware\Config as HttpMiddlewareConfig;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware as HttpRequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware as HttpRouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware as HttpRouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware as HttpRouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware as HttpSendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware as HttpTerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware as HttpThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Config as HttpRoutingConfig;
use Valkyrja\Http\Server\Contract\RequestHandler as HttpServerRequestHandler;
use Valkyrja\Jwt\Adapter\Contract\Adapter as JwtAdapter;
use Valkyrja\Jwt\Config\Configurations as JwtConfiguration;
use Valkyrja\Jwt\Driver\Contract\Driver as JwtDriver;
use Valkyrja\Log\Adapter\Contract\Adapter as LogAdapter;
use Valkyrja\Log\Config\Configurations as LogConfigurations;
use Valkyrja\Log\Driver\Contract\Driver as LogDriver;
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
use Valkyrja\Session\Adapter\Contract\Adapter as SessionAdapter;
use Valkyrja\Session\Config\Configurations as SessionConfigurations;
use Valkyrja\Session\Driver\Contract\Driver as SessionDriver;
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
 */
class Env
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null APP_ENV = 'local';
    /** @var bool|null */
    public const bool|null APP_DEBUG_MODE = true;
    /** @var string|null */
    public const string|null APP_URL = 'localhost';
    /** @var string|null */
    public const string|null APP_TIMEZONE = 'UTC';
    /** @var string|null */
    public const string|null APP_VERSION = '1 (ALPHA)';
    /** @var string|null */
    public const string|null APP_KEY = null;
    /** @var class-string<Component>[]|null */
    public const array|null APP_COMPONENTS = null;
    /** @var string|null */
    public const string|null APP_CACHE_FILE_PATH = null;

    /************************************************************
     *
     * Api component env variables.
     *
     ************************************************************/

    /** @var class-string<ApiJson>|null */
    public const string|null API_JSON_MODEL = null;
    /** @var class-string<ApiJsonData>|null */
    public const string|null API_DATA_MODEL = null;

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
     * Cli component env variables.
     *
     ************************************************************/

    /** @var callable():CliInteractionConfig|null */
    public const array|null CLI_INTERACTION = null;
    /** @var callable():CliMiddlewareConfig|null */
    public const array|null CLI_MIDDLEWARE = null;
    /** @var callable():CliRoutingConfig|null */
    public const array|null CLI_ROUTING = null;

    /************************************************************
     *
     * Cli Interaction component env variables.
     *
     ************************************************************/

    /** @var bool|null */
    public const bool|null CLI_INTERACTION_IS_QUIET = null;
    /** @var bool|null */
    public const bool|null CLI_INTERACTION_IS_INTERACTIVE = null;
    /** @var bool|null */
    public const bool|null CLI_INTERACTION_IS_SILENT = null;

    /************************************************************
     *
     * Cli Middleware component env variables.
     *
     ************************************************************/

    /** @var class-string<InputReceivedMiddleware>[]|null */
    public const array|null CLI_MIDDLEWARE_INPUT_RECEIVED = null;
    /** @var class-string<CommandMatchedMiddleware>[]|null */
    public const array|null CLI_MIDDLEWARE_COMMAND_MATCHED = null;
    /** @var class-string<CommandNotMatchedMiddleware>[]|null */
    public const array|null CLI_MIDDLEWARE_COMMAND_NOT_MATCHED = null;
    /** @var class-string<CommandDispatchedMiddleware>[]|null */
    public const array|null CLI_MIDDLEWARE_COMMAND_DISPATCHED = null;
    /** @var class-string<ThrowableCaughtMiddleware>[]|null */
    public const array|null CLI_MIDDLEWARE_THROWABLE_CAUGHT = null;
    /** @var class-string<ExitedMiddleware>[]|null */
    public const array|null CLI_MIDDLEWARE_EXITED = null;

    /************************************************************
     *
     * Cli Routing component env variables.
     *
     ************************************************************/

    /** @var class-string[]|null */
    public const array|null CLI_ROUTING_CONTROLLERS = null;

    /************************************************************
     *
     * Container component env variables.
     *
     ************************************************************/

    /** @var class-string[]|null */
    public const array|null CONTAINER_ALIASES = null;
    /** @var class-string<ContainerService>[]|null */
    public const array|null CONTAINER_SERVICES = null;
    /** @var class-string[]|null */
    public const array|null CONTAINER_CONTEXT_ALIASES = null;
    /** @var class-string<ContainerService>[]|null */
    public const array|null CONTAINER_CONTEXT_SERVICES = null;
    /** @var class-string<ContainerProvider>[]|null */
    public const array|null CONTAINER_PROVIDERS = null;
    /** @var bool|null */
    public const bool|null CONTAINER_USE_ATTRIBUTES = null;

    /************************************************************
     *
     * Event component env variables.
     *
     ************************************************************/

    /** @var class-string[]|null */
    public const array|null EVENT_LISTENERS = null;

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
     * Http component env variables.
     *
     ************************************************************/

    /** @var callable():HttpMiddlewareConfig|null */
    public const array|null HTTP_MIDDLEWARE = null;
    /** @var callable():HttpRoutingConfig|null */
    public const array|null HTTP_ROUTING = null;

    /************************************************************
     *
     * Http Middleware component env variables.
     *
     ************************************************************/

    /** @var class-string<HttpRequestReceivedMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_REQUEST_RECEIVED = null;
    /** @var class-string<HttpRouteDispatchedMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_DISPATCHED = null;
    /** @var class-string<HttpRouteMatchedMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_THROWABLE_CAUGHT = null;
    /** @var class-string<HttpRouteNotMatchedMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_MATCHED = null;
    /** @var class-string<HttpThrowableCaughtMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED = null;
    /** @var class-string<HttpSendingResponseMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_SENDING_RESPONSE = null;
    /** @var class-string<HttpTerminatedMiddleware>[]|null */
    public const array|null HTTP_MIDDLEWARE_TERMINATED = null;

    /************************************************************
     *
     * Http Routing component env variables.
     *
     ************************************************************/

    /** @var class-string[]|null */
    public const array|null HTTP_ROUTING_CONTROLLERS = null;

    /************************************************************
     *
     * Http Server component env variables.
     *
     ************************************************************/

    /** @var class-string<HttpServerRequestHandler>|null */
    public const string|null HTTP_SERVER_REQUEST_HANDLER = null;

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
     * Logger component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null LOG_DEFAULT_CONFIGURATION = null;
    /** @var callable():LogConfigurations|null */
    public const array|null LOG_CONFIGURATIONS = null;
    /** @var class-string<LogAdapter>|null */
    public const string|null LOG_PSR_ADAPTER_CLASS = null;
    /** @var class-string<LogDriver>|null */
    public const string|null LOG_PSR_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null LOG_PSR_NAME = null;
    /** @var string|null */
    public const string|null LOG_PSR_FILE_PATH = null;
    /** @var class-string<LogAdapter>|null */
    public const string|null LOG_NULL_ADAPTER_CLASS = null;
    /** @var class-string<LogDriver>|null */
    public const string|null LOG_NULL_DRIVER_CLASS = null;

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
     * Notification component env variables.
     *
     ************************************************************/

    /** @var array<string, mixed>|null */
    public const array|null NOTIFICATION_NOTIFICATIONS = null;

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

    /** @var string|null */
    public const string|null SESSION_DEFAULT_CONFIGURATION = null;
    /** @var callable():SessionConfigurations|null */
    public const array|null SESSION_CONFIGURATIONS = null;
    /** @var class-string<SessionAdapter>|null */
    public const string|null SESSION_PHP_ADAPTER_CLASS = null;
    /** @var class-string<SessionDriver>|null */
    public const string|null SESSION_PHP_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null SESSION_PHP_ID = null;
    /** @var string|null */
    public const string|null SESSION_PHP_NAME = null;
    /** @var class-string<SessionAdapter>|null */
    public const string|null SESSION_NULL_ADAPTER_CLASS = null;
    /** @var class-string<SessionDriver>|null */
    public const string|null SESSION_NULL_DRIVER_CLASS = null;
    /** @var class-string<SessionAdapter>|null */
    public const string|null SESSION_CACHE_ADAPTER_CLASS = null;
    /** @var class-string<SessionDriver>|null */
    public const string|null SESSION_CACHE_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null SESSION_CACHE_CACHE = null;
    /** @var class-string<SessionAdapter>|null */
    public const string|null SESSION_COOKIE_ADAPTER_CLASS = null;
    /** @var class-string<SessionDriver>|null */
    public const string|null SESSION_COOKIE_DRIVER_CLASS = null;
    /** @var class-string<SessionAdapter>|null */
    public const string|null SESSION_LOG_ADAPTER_CLASS = null;
    /** @var class-string<SessionDriver>|null */
    public const string|null SESSION_LOG_DRIVER_CLASS = null;
    /** @var string|null */
    public const string|null SESSION_LOG_LOGGER = null;
    /** @var string|null */
    public const string|null SESSION_COOKIE_PARAM_PATH = null;
    /** @var string|null */
    public const string|null SESSION_COOKIE_PARAM_DOMAIN = null;
    /** @var int|null */
    public const int|null SESSION_COOKIE_PARAM_LIFETIME = null;
    /** @var bool|null */
    public const bool|null SESSION_COOKIE_PARAM_SECURE = null;
    /** @var bool|null */
    public const bool|null SESSION_COOKIE_PARAM_HTTP_ONLY = null;
    /** @var SameSite|null */
    public const SameSite|null SESSION_COOKIE_PARAM_SAME_SITE = null;

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
