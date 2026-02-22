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

namespace Valkyrja\Application\Env;

use Twig\Extension\ExtensionInterface;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract as HttpRouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract as HttpRouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract as HttpRouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract as HttpThrowableCaughtMiddlewareContract;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

/**
 * @see https://psalm.dev/r/eb18b4d7ae This one is fine
 * @see https://psalm.dev/r/36fd31ac0e This on breaks, the moment we use an annotation?!!?!?
 */
class Env
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var non-empty-string */
    public const string APP_ENVIRONMENT = 'local';
    /** @var non-empty-string */
    public const string APP_NAMESPACE = 'App';
    /** @var bool */
    public const bool APP_DEBUG_MODE = true;
    /** @var non-empty-string */
    public const string APP_TIMEZONE = 'UTC';
    /** @var non-empty-string|null */
    public const string|null APP_VERSION = null;
    /** @var non-empty-string */
    public const string APP_KEY = 'some_secret_app_key';
    /** @var class-string<Provider>[] */
    public const array APP_REQUIRED_COMPONENTS = [
        ComponentClass::ATTRIBUTE,
        ComponentClass::CONTAINER,
        ComponentClass::DISPATCHER,
        ComponentClass::REFLECTION,
    ];
    /** @var class-string<Provider>[] */
    public const array APP_CORE_COMPONENTS = [
        ComponentClass::CLI_INTERACTION,
        ComponentClass::CLI_MIDDLEWARE,
        ComponentClass::CLI_ROUTING,
        ComponentClass::CLI_SERVER,
        ComponentClass::EVENT,
        ComponentClass::HTTP_MESSAGE,
        ComponentClass::HTTP_MIDDLEWARE,
        ComponentClass::HTTP_ROUTING,
        ComponentClass::HTTP_SERVER,
    ];
    /** @var class-string<Provider>[] */
    public const array APP_COMPONENTS = [
        ComponentClass::API,
        ComponentClass::AUTH,
        ComponentClass::BROADCAST,
        ComponentClass::CACHE,
        ComponentClass::CRYPT,
        ComponentClass::FILESYSTEM,
        ComponentClass::HTTP_CLIENT,
        ComponentClass::JWT,
        ComponentClass::LOG,
        ComponentClass::MAIL,
        ComponentClass::ORM,
        ComponentClass::SESSION,
        ComponentClass::SMS,
        ComponentClass::VIEW,
    ];
    /** @var class-string<Provider>[] */
    public const array APP_CUSTOM_COMPONENTS = [];
    /** @var non-empty-string|null */
    public const string|null APP_CLI_DEFAULT_APPLICATION_NAME = null;
    /** @var non-empty-string|null */
    public const string|null APP_CLI_DEFAULT_COMMAND_NAME = null;
    /** @var non-empty-string */
    public const string APP_DIR = __DIR__ . '/..';

    /************************************************************
     *
     * Auth component env variables.
     *
     ************************************************************/

    /** @var class-string<AuthenticatorContract>|null */
    public const string|null AUTH_DEFAULT_AUTHENTICATOR = null;
    /** @var class-string<StoreContract>|null */
    public const string|null AUTH_DEFAULT_STORE = null;
    /** @var class-string<UserContract>|null */
    public const string|null AUTH_DEFAULT_USER_ENTITY = null;
    /** @var non-empty-string|null */
    public const string|null AUTH_SESSION_ITEM_ID = null;
    /** @var class-string<AuthenticatedUsersContract>[]|null */
    public const array|null AUTH_SESSION_ALLOWED_CLASSES = null;

    /************************************************************
     *
     * Broadcast component env variables.
     *
     ************************************************************/

    /** @var class-string<BroadcasterContract>|null */
    public const string|null BROADCAST_DEFAULT_BROADCASTER = null;
    /** @var non-empty-string|null */
    public const string|null BROADCAST_PUSHER_KEY = null;
    /** @var non-empty-string|null */
    public const string|null BROADCAST_PUSHER_SECRET = null;
    /** @var non-empty-string|null */
    public const string|null BROADCAST_PUSHER_ID = null;
    /** @var non-empty-string|null */
    public const string|null BROADCAST_PUSHER_CLUSTER = null;
    /** @var bool|null */
    public const bool|null BROADCAST_PUSHER_USE_TLS = null;
    /** @var class-string<LoggerContract>|null */
    public const string|null BROADCAST_LOG_LOGGER = null;

    /************************************************************
     *
     * Cache component env variables.
     *
     ************************************************************/

    /** @var class-string<CacheContract>|null */
    public const string|null CACHE_DEFAULT = null;
    /** @var non-empty-string|null */
    public const string|null CACHE_REDIS_HOST = null;
    /** @var int|null */
    public const int|null CACHE_REDIS_PORT = null;
    /** @var string|null */
    public const string|null CACHE_REDIS_PREFIX = null;
    /** @var string|null */
    public const string|null CACHE_LOG_PREFIX = null;
    /** @var class-string<LoggerContract>|null */
    public const string|null CACHE_LOG_LOGGER = null;
    /** @var string|null */
    public const string|null CACHE_NULL_PREFIX = null;

    /************************************************************
     *
     * Cli component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null CLI_HELP_COMMAND_NAME = null;
    /** @var non-empty-string|null */
    public const string|null CLI_HELP_OPTION_NAME = null;
    /** @var non-empty-string|null */
    public const string|null CLI_HELP_OPTION_SHORT_NAME = null;
    /** @var non-empty-string|null */
    public const string|null CLI_VERSION_COMMAND_NAME = null;
    /** @var non-empty-string|null */
    public const string|null CLI_VERSION_OPTION_NAME = null;
    /** @var non-empty-string|null */
    public const string|null CLI_VERSION_OPTION_SHORT_NAME = null;

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

    /** @var class-string<InputReceivedMiddlewareContract>[]|null */
    public const array|null CLI_MIDDLEWARE_INPUT_RECEIVED = null;
    /** @var class-string<RouteMatchedMiddlewareContract>[]|null */
    public const array|null CLI_MIDDLEWARE_COMMAND_MATCHED = null;
    /** @var class-string<RouteNotMatchedMiddlewareContract>[]|null */
    public const array|null CLI_MIDDLEWARE_COMMAND_NOT_MATCHED = null;
    /** @var class-string<RouteDispatchedMiddlewareContract>[]|null */
    public const array|null CLI_MIDDLEWARE_COMMAND_DISPATCHED = null;
    /** @var class-string<ThrowableCaughtMiddlewareContract>[]|null */
    public const array|null CLI_MIDDLEWARE_THROWABLE_CAUGHT = null;
    /** @var class-string<ExitedMiddlewareContract>[]|null */
    public const array|null CLI_MIDDLEWARE_EXITED = null;

    /************************************************************
     *
     * Cli Routing component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null CLI_ROUTING_COLLECTION_DATA_FILE_PATH = null;
    /** @var bool|null */
    public const bool|null CLI_ROUTING_COLLECTION_USE_DATA = null;

    /************************************************************
     *
     * Container component env variables.
     *
     ************************************************************/

    /** @var bool|null */
    public const bool|null CONTAINER_USE_DATA = null;
    /** @var non-empty-string|null */
    public const string|null CONTAINER_DATA_FILE_PATH = null;

    /************************************************************
     *
     * Crypt component env variables.
     *
     ************************************************************/

    /** @var class-string<CryptContract>|null */
    public const string|null CRYPT_DEFAULT = null;

    /************************************************************
     *
     * Event component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null EVENT_COLLECTION_DATA_FILE_PATH = null;
    /** @var bool|null */
    public const bool|null EVENT_COLLECTION_USE_DATA = null;

    /************************************************************
     *
     * Filesystem component env variables.
     *
     ************************************************************/

    /** @var class-string<FilesystemContract>|null */
    public const string|null FILESYSTEM_DEFAULT = null;
    /** @var class-string<FlysystemFilesystem>|null */
    public const string|null FLYSYSTEM_FILESYSTEM_DEFAULT = null;
    /** @var non-empty-string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_LOCAL_PATH = null;
    /** @var non-empty-string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_KEY = null;
    /** @var non-empty-string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_SECRET = null;
    /** @var non-empty-string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_REGION = null;
    /** @var non-empty-string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_VERSION = null;
    /** @var non-empty-string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_BUCKET = null;
    /** @var string|null */
    public const string|null FILESYSTEM_FLYSYSTEM_S3_PREFIX = null;
    /** @var array<string, mixed>|null */
    public const array|null FILESYSTEM_FLYSYSTEM_S3_OPTIONS = null;

    /************************************************************
     *
     * Http Client component env variables.
     *
     ************************************************************/

    /** @var class-string<ClientContract>|null */
    public const string|null HTTP_CLIENT_DEFAULT = null;

    /************************************************************
     *
     * Http Middleware component env variables.
     *
     ************************************************************/

    /** @var class-string<RequestReceivedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_REQUEST_RECEIVED = null;
    /** @var class-string<HttpRouteDispatchedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_DISPATCHED = null;
    /** @var class-string<HttpThrowableCaughtMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_THROWABLE_CAUGHT = null;
    /** @var class-string<HttpRouteMatchedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_MATCHED = null;
    /** @var class-string<HttpRouteNotMatchedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED = null;
    /** @var class-string<SendingResponseMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_SENDING_RESPONSE = null;
    /** @var class-string<TerminatedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_TERMINATED = null;

    /************************************************************
     *
     * Http Routing component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null HTTP_ROUTING_COLLECTION_DATA_FILE_PATH = null;
    /** @var bool|null */
    public const bool|null HTTP_ROUTING_COLLECTION_USE_DATA = null;

    /************************************************************
     *
     * Http Server component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null HTTP_SERVER_RESPONSE_CACHE_FILE_PATH = null;

    /************************************************************
     *
     * Jwt component env variables.
     *
     ************************************************************/

    /** @var class-string<JwtContract>|null */
    public const string|null JWT_DEFAULT = null;
    /** @var Algorithm|null */
    public const Algorithm|null JWT_ALGORITHM = null;
    /** @var non-empty-string|null */
    public const string|null JWT_HS_KEY = null;
    /** @var non-empty-string|null */
    public const string|null JWT_RS_PRIVATE_KEY = null;
    /** @var non-empty-string|null */
    public const string|null JWT_RS_PUBLIC_KEY = null;
    /** @var non-empty-string|null */
    public const string|null JWT_EDDSA_PRIVATE_KEY = null;
    /** @var non-empty-string|null */
    public const string|null JWT_EDDSA_PUBLIC_KEY = null;

    /************************************************************
     *
     * Log component env variables.
     *
     ************************************************************/

    /** @var class-string<LoggerContract>|null */
    public const string|null LOG_DEFAULT_LOGGER = null;

    /************************************************************
     *
     * Mail component env variables.
     *
     ************************************************************/

    /** @var class-string<MailerContract>|null */
    public const string|null MAIL_DEFAULT_MAILER = null;
    /** @var non-empty-string|null */
    public const string|null MAIL_MAILGUN_API_KEY = null;
    /** @var non-empty-string|null */
    public const string|null MAIL_MAILGUN_DOMAIN = null;
    /** @var non-empty-string|null */
    public const string|null MAIL_PHP_MAILER_HOST = null;
    /** @var int|null */
    public const int|null MAIL_PHP_MAILER_PORT = null;
    /** @var non-empty-string|null */
    public const string|null MAIL_PHP_MAILER_USERNAME = null;
    /** @var non-empty-string|null */
    public const string|null MAIL_PHP_MAILER_PASSWORD = null;
    /** @var non-empty-string|null */
    public const string|null MAIL_PHP_MAILER_ENCRYPTION = null;

    /************************************************************
     *
     * Orm component env variables.
     *
     ************************************************************/

    /** @var class-string<ManagerContract>|null */
    public const string|null ORM_DEFAULT_MANAGER = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_HOST = null;
    /** @var positive-int|null */
    public const int|null ORM_PGSQL_PORT = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_DB = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_USER = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_PASSWORD = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_CHARSET = null;
    /** @var array<int, int|bool>|null */
    public const array|null ORM_PGSQL_OPTIONS = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_SCHEMA = null;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_SSL_MODE = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_HOST = null;
    /** @var positive-int|null */
    public const int|null ORM_MYSQL_PORT = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_DB = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_USER = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_PASSWORD = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_CHARSET = null;
    /** @var array<int, int|bool>|null */
    public const array|null ORM_MYSQL_OPTIONS = null;
    /** @var bool|null */
    public const bool|null ORM_MYSQL_STRICT = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_ENGINE = null;
    /** @var non-empty-string|null */
    public const string|null ORM_SQLITE_HOST = null;
    /** @var positive-int|null */
    public const int|null ORM_SQLITE_PORT = null;
    /** @var non-empty-string|null */
    public const string|null ORM_SQLITE_DB = null;
    /** @var non-empty-string|null */
    public const string|null ORM_SQLITE_USER = null;
    /** @var non-empty-string|null */
    public const string|null ORM_SQLITE_PASSWORD = null;
    /** @var non-empty-string|null */
    public const string|null ORM_SQLITE_CHARSET = null;
    /** @var array<int, int|bool>|null */
    public const array|null ORM_SQLITE_OPTIONS = null;

    /************************************************************
     *
     * Session component env variables.
     *
     ************************************************************/

    /** @var class-string<SessionContract>|null */
    public const string|null SESSION_DEFAULT = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_PHP_ID = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_PHP_NAME = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_COOKIE_PARAM_PATH = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_COOKIE_PARAM_DOMAIN = null;
    /** @var int|null */
    public const int|null SESSION_COOKIE_PARAM_LIFETIME = null;
    /** @var bool|null */
    public const bool|null SESSION_COOKIE_PARAM_SECURE = null;
    /** @var bool|null */
    public const bool|null SESSION_COOKIE_PARAM_HTTP_ONLY = null;
    /** @var SameSite|null */
    public const SameSite|null SESSION_COOKIE_PARAM_SAME_SITE = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_JWT_OPTION_NAME = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_JWT_HEADER_NAME = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_TOKEN_OPTION_NAME = null;
    /** @var non-empty-string|null */
    public const string|null SESSION_TOKEN_HEADER_NAME = null;

    /************************************************************
     *
     * SMS component env variables.
     *
     ************************************************************/

    /** @var class-string<MessengerContract>|null */
    public const string|null SMS_DEFAULT_MESSENGER = null;
    /** @var non-empty-string|null */
    public const string|null SMS_VONAGE_KEY = null;
    /** @var non-empty-string|null */
    public const string|null SMS_VONAGE_SECRET = null;

    /************************************************************
     *
     * View component env variables.
     *
     ************************************************************/

    /** @var class-string<RendererContract>|null */
    public const string|null VIEW_DEFAULT_RENDERER = null;
    /** @var non-empty-string|null */
    public const string|null VIEW_ORKA_FILE_EXTENSION = null;
    /** @var non-empty-string|null */
    public const string|null VIEW_ORKA_PATH = null;
    /** @var array<non-empty-string, non-empty-string>|null */
    public const array|null VIEW_ORKA_PATHS = null;
    /** @var class-string<ReplacementContract>[]|null */
    public const array|null VIEW_ORKA_CORE_REPLACEMENTS = null;
    /** @var class-string<ReplacementContract>[]|null */
    public const array|null VIEW_ORKA_REPLACEMENTS = null;
    /** @var non-empty-string|null */
    public const string|null VIEW_PHP_FILE_EXTENSION = null;
    /** @var non-empty-string|null */
    public const string|null VIEW_PHP_PATH = null;
    /** @var array<string, string>|null */
    public const array|null VIEW_PHP_PATHS = null;
    /** @var array<string, string>|null */
    public const array|null VIEW_TWIG_PATHS = null;
    /** @var class-string<ExtensionInterface>[]|null */
    public const array|null VIEW_TWIG_EXTENSIONS = null;
    /** @var non-empty-string|null */
    public const string|null VIEW_TWIG_COMPILED_PATH = null;
}
