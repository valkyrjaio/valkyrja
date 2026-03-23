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
use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Message\Enum\SameSite;
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
     * Container component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null CONTAINER_DATA_CLASS_NAME = null;

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
    public const string|null EVENT_DATA_CLASS_NAME = null;

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
     * Http Routing component env variables.
     *
     ************************************************************/

    /** @var non-empty-string|null */
    public const string|null HTTP_ROUTING_DATA_CLASS_NAME = null;

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
