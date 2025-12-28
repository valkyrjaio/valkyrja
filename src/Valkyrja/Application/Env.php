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

use Twig\Extension\ExtensionInterface as TwigExtensionInterface;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Support\Component;
use Valkyrja\Auth\Constant\RouteName;
use Valkyrja\Auth\Constant\SessionId;
use Valkyrja\Auth\Contract\Authenticator;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Entity\User as UserEntity;
use Valkyrja\Auth\SessionAuthenticator;
use Valkyrja\Auth\Store\Contract\Store;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Broadcast\Contract\Broadcaster;
use Valkyrja\Broadcast\PusherBroadcaster;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Cache\RedisCache;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\SodiumCrypt;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Filesystem\FlysystemFilesystem;
use Valkyrja\Filesystem\LocalFlysystemFilesystem;
use Valkyrja\Http\Client\Contract\Client;
use Valkyrja\Http\Client\GuzzleClient;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware as HttpRequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware as HttpRouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware as HttpRouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware as HttpRouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware as HttpSendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware as HttpTerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware as HttpThrowableCaughtMiddleware;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\FirebaseJwt;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Mail\Manager\Contract\Mailer;
use Valkyrja\Mail\Manager\MailgunMailer;
use Valkyrja\Orm\Manager\Contract\Manager;
use Valkyrja\Orm\Manager\MysqlManager;
use Valkyrja\Session\Manager\Contract\Session;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Sms\Messenger\Contract\Messenger;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Valkyrja\View\Renderer\Contract\Renderer;
use Valkyrja\View\Renderer\PhpRenderer;

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
    /** @var non-empty-string */
    public const string APP_NAMESPACE = 'App';
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
    public const array APP_REQUIRED_COMPONENTS = [
        ComponentClass::CONTAINER,
        ComponentClass::APPLICATION,
        ComponentClass::ATTRIBUTE,
        ComponentClass::DISPATCHER,
        ComponentClass::REFLECTION,
    ];
    /** @var class-string<Component>[] */
    public const array APP_CORE_COMPONENTS = [
        ComponentClass::CLI,
        ComponentClass::EVENT,
        ComponentClass::HTTP,
    ];
    /** @var class-string<Component>[] */
    public const array APP_COMPONENTS = [
        ComponentClass::API,
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
    /** @var class-string<Component>[] */
    public const array APP_CUSTOM_COMPONENTS = [];
    /** @var bool */
    public const bool APP_ADD_CLI_CONTROLLERS = true;
    /** @var bool */
    public const bool APP_ADD_HTTP_CONTROLLERS = true;
    /** @var bool */
    public const bool APP_ADD_EVENT_LISTENERS = true;
    /** @var non-empty-string */
    public const string APP_DIR = __DIR__ . '/..';
    /** @var non-empty-string */
    public const string APP_CACHE_FILE_PATH = __DIR__ . '/../storage/framework/cache/cache.php';

    /************************************************************
     *
     * Auth component env variables.
     *
     ************************************************************/

    /** @var class-string<Authenticator> */
    public const string AUTH_DEFAULT_AUTHENTICATOR = SessionAuthenticator::class;
    /** @var class-string<Store> */
    public const string AUTH_DEFAULT_STORE = OrmStore::class;
    /** @var class-string<User> */
    public const string AUTH_DEFAULT_USER_ENTITY = UserEntity::class;
    /** @var non-empty-string */
    public const string AUTH_DEFAULT_SESSION_ID = SessionId::AUTHENTICATED_USERS;
    /** @var non-empty-string */
    public const string AUTH_DEFAULT_AUTHORIZATION_HEADER = HeaderName::AUTHORIZATION;
    /** @var non-empty-string */
    public const string AUTH_AUTHENTICATE_ROUTE = RouteName::AUTHENTICATE;
    /** @var non-empty-string|null */
    public const string|null AUTH_AUTHENTICATE_URL = null;
    /** @var non-empty-string */
    public const string AUTH_NOT_AUTHENTICATED_ROUTE = RouteName::DASHBOARD;
    /** @var non-empty-string|null */
    public const string|null AUTH_NOT_AUTHENTICATED_URL = null;
    /** @var non-empty-string */
    public const string AUTH_PASSWORD_CONFIRM_ROUTE = RouteName::PASSWORD_CONFIRM;
    /** @var positive-int */
    public const int AUTH_PASSWORD_TIMEOUT = 10800;

    /************************************************************
     *
     * Broadcast component env variables.
     *
     ************************************************************/

    /** @var class-string<Broadcaster> */
    public const string BROADCAST_DEFAULT_BROADCASTER = PusherBroadcaster::class;
    /** @var non-empty-string */
    public const string BROADCAST_PUSHER_KEY = 'pusher-key';
    /** @var non-empty-string */
    public const string BROADCAST_PUSHER_SECRET = 'pusher-secret';
    /** @var non-empty-string */
    public const string BROADCAST_PUSHER_ID = 'pusher-id';
    /** @var non-empty-string */
    public const string BROADCAST_PUSHER_CLUSTER = 'us1';
    /** @var bool */
    public const bool BROADCAST_PUSHER_USE_TLS = true;
    /** @var class-string<Logger> */
    public const string BROADCAST_LOG_LOGGER = Logger::class;

    /************************************************************
     *
     * Cache component env variables.
     *
     ************************************************************/

    /** @var class-string<Cache> */
    public const string CACHE_DEFAULT = RedisCache::class;
    /** @var non-empty-string */
    public const string CACHE_REDIS_HOST = '127.0.0.1';
    /** @var int */
    public const int CACHE_REDIS_PORT = 6379;
    /** @var string */
    public const string CACHE_REDIS_PREFIX = '';
    /** @var string */
    public const string CACHE_LOG_PREFIX = '';
    /** @var class-string<Logger> */
    public const string CACHE_LOG_LOGGER = Logger::class;
    /** @var string */
    public const string CACHE_NULL_PREFIX = '';

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
     * Crypt component env variables.
     *
     ************************************************************/

    /** @var class-string<Crypt> */
    public const string CRYPT_DEFAULT = SodiumCrypt::class;

    /************************************************************
     *
     * Filesystem component env variables.
     *
     ************************************************************/

    /** @var class-string<Filesystem> */
    public const string FILESYSTEM_DEFAULT = FlysystemFilesystem::class;
    /** @var class-string<FlysystemFilesystem> */
    public const string FLYSYSTEM_FILESYSTEM_DEFAULT = LocalFlysystemFilesystem::class;
    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_LOCAL_DIR = __DIR__ . '/../storage/app';
    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_S3_KEY = 's3-key';
    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_S3_SECRET = 's3-secret';
    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_S3_REGION = 'us-east-1';
    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_S3_VERSION = 'latest';
    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_S3_BUCKET = 's3-bucket';
    /** @var string */
    public const string FILESYSTEM_FLYSYSTEM_S3_PREFIX = '';
    /** @var array<string, mixed> */
    public const array FILESYSTEM_FLYSYSTEM_S3_OPTIONS = [];

    /************************************************************
     *
     * Http Client component env variables.
     *
     ************************************************************/

    /** @var class-string<Client> */
    public const string HTTP_CLIENT_DEFAULT = GuzzleClient::class;

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

    /** @var class-string<Jwt> */
    public const string JWT_DEFAULT = FirebaseJwt::class;
    /** @var Algorithm */
    public const Algorithm JWT_ALGORITHM = Algorithm::HS256;
    /** @var non-empty-string */
    public const string JWT_HS_KEY = 'key';
    /** @var non-empty-string */
    public const string JWT_RS_PRIVATE_KEY = 'private-key';
    /** @var non-empty-string */
    public const string JWT_RS_PUBLIC_KEY = 'public-key';
    /** @var non-empty-string */
    public const string JWT_EDDSA_PRIVATE_KEY = 'private-key';
    /** @var non-empty-string */
    public const string JWT_EDDSA_PUBLIC_KEY = 'public-key';

    /************************************************************
     *
     * Log component env variables.
     *
     ************************************************************/

    /** @var class-string<Logger> */
    public const string LOG_DEFAULT_LOGGER = PsrLogger::class;

    /************************************************************
     *
     * Mail component env variables.
     *
     ************************************************************/

    /** @var class-string<Mailer> */
    public const string MAIL_DEFAULT_MAILER = MailgunMailer::class;
    /** @var non-empty-string */
    public const string MAIL_MAILGUN_API_KEY = 'api-key';
    /** @var non-empty-string */
    public const string MAIL_MAILGUN_DOMAIN = 'domain';
    /** @var non-empty-string */
    public const string MAIL_PHP_MAILER_HOST = 'host';
    /** @var int */
    public const int MAIL_PHP_MAILER_PORT = 25;
    /** @var non-empty-string */
    public const string MAIL_PHP_MAILER_USERNAME = 'username';
    /** @var non-empty-string */
    public const string MAIL_PHP_MAILER_PASSWORD = 'password';
    /** @var non-empty-string */
    public const string MAIL_PHP_MAILER_ENCRYPTION = 'ssl';

    /************************************************************
     *
     * Orm component env variables.
     *
     ************************************************************/

    /** @var class-string<Manager> */
    public const string ORM_DEFAULT_MANAGER = MysqlManager::class;
    /** @var non-empty-string */
    public const string ORM_PGSQL_HOST = '127.0.0.1';
    /** @var positive-int */
    public const int ORM_PGSQL_PORT = 6379;
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_DB = 'valkyrja';
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_USER = 'valkyrja';
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_PASSWORD = 'pgsql-password';
    /** @var non-empty-string|null */
    public const string|null ORM_PGSQL_CHARSET = 'utf8';
    /** @var array<int, int|bool>|null */
    public const array|null ORM_PGSQL_OPTIONS = null;
    /** @var non-empty-string */
    public const string ORM_PGSQL_SCHEMA = 'public';
    /** @var non-empty-string */
    public const string ORM_PGSQL_SSL_MODE = 'prefer';
    /** @var non-empty-string */
    public const string ORM_MYSQL_HOST = '127.0.0.1';
    /** @var positive-int */
    public const int ORM_MYSQL_PORT = 3306;
    /** @var non-empty-string */
    public const string ORM_MYSQL_DB = 'valkyrja';
    /** @var non-empty-string */
    public const string ORM_MYSQL_USER = 'valkyrja';
    /** @var non-empty-string */
    public const string ORM_MYSQL_PASSWORD = 'mysql-password';
    /** @var non-empty-string */
    public const string ORM_MYSQL_CHARSET = 'utf8mb4';
    /** @var array<int, int|bool>|null */
    public const array|null ORM_MYSQL_OPTIONS = null;
    /** @var bool|null */
    public const bool|null ORM_MYSQL_STRICT = null;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_ENGINE = null;
    /** @var non-empty-string */
    public const string ORM_SQLITE_HOST = '127.0.0.1';
    /** @var positive-int */
    public const int ORM_SQLITE_PORT = 3306;
    /** @var non-empty-string */
    public const string ORM_SQLITE_DB = 'valkyrja';
    /** @var non-empty-string */
    public const string ORM_SQLITE_USER = 'valkyrja';
    /** @var non-empty-string */
    public const string ORM_SQLITE_PASSWORD = 'sqlite-password';
    /** @var non-empty-string */
    public const string ORM_SQLITE_CHARSET = 'utf8';
    /** @var array<int, int|bool>|null */
    public const array|null ORM_SQLITE_OPTIONS = null;

    /************************************************************
     *
     * Session component env variables.
     *
     ************************************************************/

    /** @var class-string<Session> */
    public const string SESSION_DEFAULT = PhpSession::class;
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
    /** @var class-string<Messenger> */
    public const string SMS_DEFAULT_MESSENGER = VonageMessenger::class;
    /** @var non-empty-string */
    public const string SMS_VONAGE_KEY = 'vonage-key';
    /** @var non-empty-string */
    public const string SMS_VONAGE_SECRET = 'vonage-secret';

    /************************************************************
     *
     * View component env variables.
     *
     ************************************************************/
    /** @var class-string<Renderer> */
    public const string VIEW_DEFAULT_RENDERER = PhpRenderer::class;
    /** @var non-empty-string */
    public const string VIEW_ORKA_FILE_EXTENSION = '.orka.phtml';
    /** @var non-empty-string */
    public const string VIEW_ORKA_DIR = __DIR__ . '/../resources/views';
    /** @var array<string, string> */
    public const array VIEW_ORKA_PATHS = [];
    /** @var non-empty-string */
    public const string VIEW_PHP_FILE_EXTENSION = '.phtml';
    /** @var non-empty-string */
    public const string VIEW_PHP_DIR = __DIR__ . '/../resources/views';
    /** @var array<string, string> */
    public const array VIEW_PHP_PATHS = [];
    /** @var array<string, string> */
    public const array VIEW_TWIG_PATHS = [];
    /** @var class-string<TwigExtensionInterface>[] */
    public const array VIEW_TWIG_EXTENSIONS = [];
    /** @var non-empty-string */
    public const string VIEW_TWIG_COMPILED_DIR = __DIR__ . '/../storage/views';
}
