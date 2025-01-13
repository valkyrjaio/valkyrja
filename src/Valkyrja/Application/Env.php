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

/**
 * Class Env.
 *
 * @author Melech Mizrachi
 */
class Env
{
    /**
     * Application env variables.
     */
    public const APP_ENV            = 'local';
    public const APP_DEBUG          = true;
    public const APP_URL            = 'localhost';
    public const APP_TIMEZONE       = 'UTC';
    public const APP_VERSION        = '1 (ALPHA)';
    public const APP_KEY            = null;
    public const APP_PATH_REGEX_MAP = null;
    public const APP_ERROR_HANDLER  = null;
    public const APP_HTTP_KERNEL    = null;
    public const APP_PROVIDERS      = null;

    /**
     * Config env variables.
     */
    public const CONFIG_PROVIDERS       = null;
    public const CONFIG_FILE_PATH       = null;
    public const CONFIG_CACHE_FILE_PATH = null;
    public const CONFIG_USE_CACHE_FILE  = null;

    /**
     * Api env variables.
     */
    public const API_JSON_MODEL      = null;
    public const API_JSON_DATA_MODEL = null;

    /**
     * Annotation env variables.
     */
    public const ANNOTATIONS_ENABLED   = null;
    public const ANNOTATIONS_CACHE_DIR = null;
    public const ANNOTATIONS_MAP       = null;

    /**
     * Asset env variables.
     */
    public const ASSET_DEFAULT          = null;
    public const ASSET_ADAPTERS         = null;
    public const ASSET_BUNDLES          = null;
    public const ASSET_DEFAULT_HOST     = null;
    public const ASSET_DEFAULT_PATH     = null;
    public const ASSET_DEFAULT_MANIFEST = null;

    /**
     * Auth env variables.
     */
    public const AUTH_ADAPTER                = null;
    public const AUTH_USER_ENTITY            = null;
    public const AUTH_REPOSITORY             = null;
    public const AUTH_GATE                   = null;
    public const AUTH_POLICY                 = null;
    public const AUTH_ALWAYS_AUTHENTICATE    = null;
    public const AUTH_KEEP_USER_FRESH        = null;
    public const AUTH_AUTHENTICATE_ROUTE     = null;
    public const AUTH_PASSWORD_CONFIRM_ROUTE = null;
    public const AUTH_USE_SESSION            = null;

    /**
     * Broadcast env variables.
     */
    public const BROADCAST_DEFAULT         = null;
    public const BROADCAST_DEFAULT_MESSAGE = null;
    public const BROADCAST_ADAPTER         = null;
    public const BROADCAST_DRIVER          = null;
    public const BROADCAST_MESSAGE         = null;
    public const BROADCAST_BROADCASTERS    = null;
    public const BROADCAST_MESSAGES        = null;
    public const BROADCAST_LOG_ADAPTER     = null;
    public const BROADCAST_LOG_DRIVER      = null;
    public const BROADCAST_LOG_LOGGER      = null;
    public const BROADCAST_NULL_ADAPTER    = null;
    public const BROADCAST_NULL_DRIVER     = null;
    public const BROADCAST_PUSHER_ADAPTER  = null;
    public const BROADCAST_PUSHER_DRIVER   = null;
    public const BROADCAST_PUSHER_KEY      = null;
    public const BROADCAST_PUSHER_SECRET   = null;
    public const BROADCAST_PUSHER_ID       = null;
    public const BROADCAST_PUSHER_CLUSTER  = null;
    public const BROADCAST_PUSHER_USE_TLS  = null;

    /**
     * Cache env variables.
     */
    public const CACHE_DEFAULT       = null;
    public const CACHE_ADAPTER       = null;
    public const CACHE_DRIVER        = null;
    public const CACHE_STORES        = null;
    public const CACHE_REDIS_ADAPTER = null;
    public const CACHE_REDIS_DRIVER  = null;
    public const CACHE_REDIS_HOST    = null;
    public const CACHE_REDIS_PORT    = null;
    public const CACHE_REDIS_PREFIX  = null;
    public const CACHE_NULL_ADAPTER  = null;
    public const CACHE_NULL_DRIVER   = null;
    public const CACHE_NULL_PREFIX   = null;
    public const CACHE_LOG_ADAPTER   = null;
    public const CACHE_LOG_DRIVER    = null;
    public const CACHE_LOG_LOGGER    = null;
    public const CACHE_LOG_PREFIX    = null;

    /**
     * Client env variables.
     */
    public const CLIENT_DEFAULT = null;
    public const CLIENT_ADAPTER = null;
    public const CLIENT_DRIVER  = null;
    public const CLIENT_CLIENTS = null;

    /**
     * Console env variables.
     */
    public const CONSOLE_PROVIDERS       = null;
    public const CONSOLE_DEV_PROVIDERS   = null;
    public const CONSOLE_QUIET           = null;
    public const CONSOLE_USE_ANNOTATIONS = null;
    public const CONSOLE_HANDLERS        = null;
    public const CONSOLE_FILE_PATH       = null;
    public const CONSOLE_CACHE_FILE_PATH = null;
    public const CONSOLE_USE_CACHE_FILE  = null;

    /**
     * Container env variables.
     */
    public const CONTAINER_PROVIDERS        = null;
    public const CONTAINER_DEV_PROVIDERS    = null;
    public const CONTAINER_USE_ANNOTATIONS  = null;
    public const CONTAINER_SERVICES         = null;
    public const CONTAINER_CONTEXT_SERVICES = null;
    public const CONTAINER_FILE_PATH        = null;
    public const CONTAINER_CACHE_FILE_PATH  = null;
    public const CONTAINER_USE_CACHE_FILE   = null;

    /**
     * Crypt env variables.
     */
    public const CRYPT_KEY             = null;
    public const CRYPT_KEY_PATH        = null;
    public const CRYPT_DEFAULT         = null;
    public const CRYPT_ADAPTERS        = null;
    public const CRYPT_DRIVERS         = null;
    public const CRYPT_CRYPTS          = null;
    public const CRYPT_DEFAULT_ADAPTER = null;
    public const CRYPT_DEFAULT_DRIVER  = null;

    /**
     * Events env variables.
     */
    public const EVENT_USE_ANNOTATIONS = null;
    public const EVENT_LISTENERS       = null;
    public const EVENT_FILE_PATH       = null;
    public const EVENT_CACHE_FILE_PATH = null;
    public const EVENTS_USE_CACHE_FILE = null;

    /**
     * Filesystem env variables.
     */
    public const FILESYSTEM_DEFAULT                 = null;
    public const FILESYSTEM_ADAPTER                 = null;
    public const FILESYSTEM_DRIVER                  = null;
    public const FILESYSTEM_DISKS                   = null;
    public const FILESYSTEM_LOCAL_ADAPTER           = null;
    public const FILESYSTEM_LOCAL_DRIVER            = null;
    public const FILESYSTEM_LOCAL_FLYSYSTEM_ADAPTER = null;
    public const FILESYSTEM_LOCAL_DIR               = null;
    public const FILESYSTEM_S3_ADAPTER              = null;
    public const FILESYSTEM_S3_DRIVER               = null;
    public const FILESYSTEM_S3_FLYSYSTEM_ADAPTER    = null;
    public const FILESYSTEM_S3_KEY                  = null;
    public const FILESYSTEM_S3_SECRET               = null;
    public const FILESYSTEM_S3_REGION               = null;
    public const FILESYSTEM_S3_VERSION              = null;
    public const FILESYSTEM_S3_BUCKET               = null;
    public const FILESYSTEM_S3_PREFIX               = null;
    public const FILESYSTEM_S3_OPTIONS              = null;

    /**
     * JWT env variables.
     */
    public const JWT_DEFAULT           = null;
    public const JWT_ADAPTER           = null;
    public const JWT_DRIVER            = null;
    public const JWT_ALGOS             = null;
    public const JWT_HS_ADAPTER        = null;
    public const JWT_HS_DRIVER         = null;
    public const JWT_HS_KEY            = null;
    public const JWT_RS_ADAPTER        = null;
    public const JWT_RS_DRIVER         = null;
    public const JWT_RS_PRIVATE_KEY    = null;
    public const JWT_RS_PUBLIC_KEY     = null;
    public const JWT_RS_KEY_PATH       = null;
    public const JWT_RS_PASSPHRASE     = null;
    public const JWT_EDDSA_ADAPTER     = null;
    public const JWT_EDDSA_DRIVER      = null;
    public const JWT_EDDSA_PRIVATE_KEY = null;
    public const JWT_EDDSA_PUBLIC_KEY  = null;

    /**
     * Logger env variables.
     */
    public const LOG_NAME      = null;
    public const LOG_FILE_PATH = null;
    public const LOG_DEFAULT   = null;
    public const LOG_ADAPTER   = null;
    public const LOG_DRIVER    = null;
    public const LOG_LOGGERS   = null;

    /**
     * Mail env variables.
     */
    public const MAIL_FROM_ADDRESS          = null;
    public const MAIL_FROM_NAME             = null;
    public const MAIL_DEFAULT               = null;
    public const MAIL_ADAPTER               = null;
    public const MAIL_DRIVER                = null;
    public const MAIL_MESSAGE               = null;
    public const MAIL_MAILERS               = null;
    public const MAIL_MESSAGES              = null;
    public const MAIL_LOG_DRIVER            = null;
    public const MAIL_LOG_ADAPTER           = null;
    public const MAIL_LOG_LOGGER            = null;
    public const MAIL_NULL_ADAPTER          = null;
    public const MAIL_NULL_DRIVER           = null;
    public const MAIL_PHP_MAILER_ADAPTER    = null;
    public const MAIL_PHP_MAILER_DRIVER     = null;
    public const MAIL_PHP_MAILER_HOST       = null;
    public const MAIL_PHP_MAILER_PORT       = null;
    public const MAIL_PHP_MAILER_ENCRYPTION = null;
    public const MAIL_PHP_MAILER_USERNAME   = null;
    public const MAIL_PHP_MAILER_PASSWORD   = null;
    public const MAIL_MAILGUN_ADAPTER       = null;
    public const MAIL_MAILGUN_DRIVER        = null;
    public const MAIL_MAILGUN_DOMAIN        = null;
    public const MAIL_MAILGUN_API_KEY       = null;

    /**
     * Notification env variables.
     */
    public const NOTIFICATION_NOTIFICATIONS = null;

    /**
     * ORM env variables.
     */
    public const ORM_DEFAULT             = null;
    public const ORM_ADAPTER             = null;
    public const ORM_DRIVER              = null;
    public const ORM_QUERY               = null;
    public const ORM_QUERY_BUILDER       = null;
    public const ORM_PERSISTER           = null;
    public const ORM_RETRIEVER           = null;
    public const ORM_REPOSITORY          = null;
    public const ORM_CONNECTIONS         = null;
    public const ORM_MIGRATIONS          = null;
    public const ORM_MYSQL_ADAPTER       = null;
    public const ORM_MYSQL_QUERY         = null;
    public const ORM_MYSQL_QUERY_BUILDER = null;
    public const ORM_MYSQL_PERSISTER     = null;
    public const ORM_MYSQL_RETRIEVER     = null;
    public const ORM_MYSQL_DRIVER        = null;
    public const ORM_MYSQL_PDO           = null;
    public const ORM_MYSQL_HOST          = null;
    public const ORM_MYSQL_PORT          = null;
    public const ORM_MYSQL_DB            = null;
    public const ORM_MYSQL_CHARSET       = null;
    public const ORM_MYSQL_USERNAME      = null;
    public const ORM_MYSQL_PASSWORD      = null;
    public const ORM_MYSQL_STRICT        = null;
    public const ORM_MYSQL_ENGINE        = null;
    public const ORM_MYSQL_OPTIONS       = null;
    public const ORM_PGSQL_ADAPTER       = null;
    public const ORM_PGSQL_QUERY         = null;
    public const ORM_PGSQL_QUERY_BUILDER = null;
    public const ORM_PGSQL_PERSISTER     = null;
    public const ORM_PGSQL_RETRIEVER     = null;
    public const ORM_PGSQL_DRIVER        = null;
    public const ORM_PGSQL_PDO           = null;
    public const ORM_PGSQL_HOST          = null;
    public const ORM_PGSQL_PORT          = null;
    public const ORM_PGSQL_DB            = null;
    public const ORM_PGSQL_USERNAME      = null;
    public const ORM_PGSQL_PASSWORD      = null;
    public const ORM_PGSQL_CHARSET       = null;
    public const ORM_PGSQL_OPTIONS       = null;
    public const ORM_PGSQL_SSL_MODE      = null;
    public const ORM_PGSQL_SSL_CERT      = null;
    public const ORM_PGSQL_SSL_KEY       = null;
    public const ORM_PGSQL_SSL_ROOT_CERT = null;
    public const ORM_PGSQL_SCHEMA        = null;

    /**
     * Path env variables.
     */
    // public const PATH_PATTERNS = null;

    /**
     * Routing env variables.
     */
    public const ROUTING_TRAILING_SLASH    = null;
    public const ROUTING_USE_ABSOLUTE_URLS = null;
    public const ROUTING_MIDDLEWARE        = null;
    public const ROUTING_MIDDLEWARE_GROUPS = null;
    public const ROUTING_USE_ANNOTATIONS   = null;
    public const ROUTING_CONTROLLERS       = null;
    public const ROUTING_FILE_PATH         = null;
    public const ROUTING_CACHE_FILE_PATH   = null;
    public const ROUTING_USE_CACHE_FILE    = null;

    /**
     * Session env variables.
     */
    public const SESSION_DEFAULT          = null;
    public const SESSION_SESSIONS         = null;
    public const SESSION_ID               = null;
    public const SESSION_NAME             = null;
    public const SESSION_ADAPTER          = null;
    public const SESSION_DRIVER           = null;
    public const SESSION_COOKIE_LIFETIME  = null;
    public const SESSION_COOKIE_PATH      = null;
    public const SESSION_COOKIE_DOMAIN    = null;
    public const SESSION_COOKIE_SECURE    = null;
    public const SESSION_COOKIE_HTTP_ONLY = null;
    public const SESSION_COOKIE_SAME_SITE = null;

    /**
     * SMS env variables.
     */
    public const SMS_ADAPTER        = null;
    public const SMS_DRIVER         = null;
    public const SMS_MESSAGE        = null;
    public const SMS_MESSAGES       = null;
    public const SMS_MESSENGERS     = null;
    public const SMS_LOG_DRIVER     = null;
    public const SMS_LOG_ADAPTER    = null;
    public const SMS_NEXMO_DRIVER   = null;
    public const SMS_NEXMO_USERNAME = null;
    public const SMS_NEXMO_PASSWORD = null;
    public const SMS_NULL_DRIVER    = null;

    /**
     * Storage env variables.
     */
    public const STORAGE_UPLOADS_DIR = null;
    public const STORAGE_LOGS_DIR    = null;

    /**
     * Validation env variables.
     */
    public const VALIDATION_RULE      = null;
    public const VALIDATION_RULES     = null;
    public const VALIDATION_RULES_MAP = null;

    /**
     * Views env variables.
     */
    public const VIEW_DIR                = null;
    public const VIEW_ENGINE             = null;
    public const VIEW_ENGINES            = null;
    public const VIEW_PATHS              = null;
    public const VIEW_DISKS              = null;
    public const VIEW_PHP_FILE_EXTENSION = null;
    public const VIEW_TWIG_COMPILED_DIR  = null;
    public const VIEW_TWIG_EXTENSIONS    = null;
}
