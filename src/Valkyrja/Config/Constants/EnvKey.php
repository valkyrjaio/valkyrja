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

namespace Valkyrja\Config\Constants;

/**
 * Constant EnvKey.
 *
 * @author Melech Mizrachi
 */
final class EnvKey
{
    public const CONFIG_CLASS                 = 'CONFIG_CLASS';
    public const CONFIG_PROVIDERS             = 'CONFIG_PROVIDERS';
    public const CONFIG_FILE_PATH             = 'CONFIG_FILE_PATH';
    public const CONFIG_CACHE_FILE_PATH       = 'CONFIG_CACHE_FILE_PATH';
    public const CONFIG_CACHE_ALLOWED_CLASSES = 'CONFIG_CACHE_ALLOWED_CLASSES';
    public const CONFIG_USE_CACHE_FILE        = 'CONFIG_USE_CACHE_FILE';

    public const API_JSON_MODEL      = 'API_JSON_MODEL';
    public const API_JSON_DATA_MODEL = 'API_JSON_DATA_MODEL';

    public const APP_ENV               = 'APP_ENV';
    public const APP_DEBUG             = 'APP_DEBUG';
    public const APP_URL               = 'APP_URL';
    public const APP_TIMEZONE          = 'APP_TIMEZONE';
    public const APP_VERSION           = 'APP_VERSION';
    public const APP_KEY               = 'APP_KEY';
    public const APP_EXCEPTION_HANDLER = 'APP_EXCEPTION_HANDLER';
    public const APP_HTTP_KERNEL       = 'APP_HTTP_KERNEL';
    public const APP_PROVIDERS         = 'APP_PROVIDERS';

    public const ANNOTATIONS_ENABLED   = 'ANNOTATIONS_ENABLED';
    public const ANNOTATIONS_CACHE_DIR = 'ANNOTATIONS_CACHE_DIR';
    public const ANNOTATIONS_MAP       = 'ANNOTATIONS_MAP';
    public const ANNOTATIONS_ALIASES   = 'ANNOTATIONS_ALIASES';

    public const ASSET_DEFAULT          = 'ASSET_DEFAULT';
    public const ASSET_ADAPTERS         = 'ASSET_ADAPTERS';
    public const ASSET_BUNDLES          = 'ASSET_BUNDLES';
    public const ASSET_DEFAULT_HOST     = 'ASSET_DEFAULT_HOST';
    public const ASSET_DEFAULT_PATH     = 'ASSET_DEFAULT_PATH';
    public const ASSET_DEFAULT_MANIFEST = 'ASSET_DEFAULT_MANIFEST';

    public const AUTH_ADAPTER                = 'AUTH_ADAPTER';
    public const AUTH_USER_ENTITY            = 'AUTH_USER_ENTITY';
    public const AUTH_REPOSITORY             = 'AUTH_REPOSITORY';
    public const AUTH_GATE                   = 'AUTH_GATE';
    public const AUTH_POLICY                 = 'AUTH_POLICY';
    public const AUTH_ALWAYS_AUTHENTICATE    = 'AUTH_ALWAYS_AUTHENTICATE';
    public const AUTH_KEEP_USER_FRESH        = 'AUTH_KEEP_USER_FRESH';
    public const AUTH_AUTHENTICATE_ROUTE     = 'AUTH_AUTHENTICATE_ROUTE';
    public const AUTH_PASSWORD_CONFIRM_ROUTE = 'AUTH_PASSWORD_CONFIRM_ROUTE';
    public const AUTH_USE_SESSION            = 'AUTH_USE_SESSION';

    public const BROADCAST_ADAPTER        = 'BROADCAST_ADAPTER';
    public const BROADCAST_ADAPTERS       = 'BROADCAST_ADAPTERS';
    public const BROADCAST_MESSAGE        = 'BROADCAST_MESSAGE';
    public const BROADCAST_MESSAGES       = 'BROADCAST_MESSAGES';
    public const BROADCAST_CACHE_DRIVER   = 'BROADCAST_CACHE_DRIVER';
    public const BROADCAST_CACHE_STORE    = 'BROADCAST_CACHE_STORE';
    public const BROADCAST_CRYPT_DRIVER   = 'BROADCAST_CRYPT_DRIVER';
    public const BROADCAST_CRYPT_ADAPTER  = 'BROADCAST_CRYPT_ADAPTER';
    public const BROADCAST_LOG_DRIVER     = 'BROADCAST_LOG_DRIVER';
    public const BROADCAST_LOG_ADAPTER    = 'BROADCAST_LOG_ADAPTER';
    public const BROADCAST_NULL_DRIVER    = 'BROADCAST_NULL_DRIVER';
    public const BROADCAST_PUSHER_DRIVER  = 'BROADCAST_PUSHER_DRIVER';
    public const BROADCAST_PUSHER_KEY     = 'BROADCAST_PUSHER_KEY';
    public const BROADCAST_PUSHER_SECRET  = 'BROADCAST_PUSHER_SECRET';
    public const BROADCAST_PUSHER_ID      = 'BROADCAST_PUSHER_ID';
    public const BROADCAST_PUSHER_CLUSTER = 'BROADCAST_PUSHER_CLUSTER';
    public const BROADCAST_PUSHER_USE_TLS = 'BROADCAST_PUSHER_USE_TLS';

    public const CACHE_DEFAULT       = 'CACHE_DEFAULT';
    public const CACHE_ADAPTERS      = 'CACHE_ADAPTERS';
    public const CACHE_DRIVERS       = 'CACHE_DRIVERS';
    public const CACHE_STORES        = 'CACHE_STORES';
    public const CACHE_REDIS_ADAPTER = 'CACHE_REDIS_ADAPTER';
    public const CACHE_REDIS_DRIVER  = 'CACHE_REDIS_DRIVER';
    public const CACHE_REDIS_HOST    = 'CACHE_REDIS_HOST';
    public const CACHE_REDIS_PORT    = 'CACHE_REDIS_PORT';
    public const CACHE_REDIS_PREFIX  = 'CACHE_REDIS_PREFIX';
    public const CACHE_NULL_ADAPTER  = 'CACHE_NULL_ADAPTER';
    public const CACHE_NULL_DRIVER   = 'CACHE_NULL_DRIVER';
    public const CACHE_NULL_PREFIX   = 'CACHE_NULL_PREFIX';
    public const CACHE_LOG_ADAPTER   = 'CACHE_LOG_ADAPTER';
    public const CACHE_LOG_DRIVER    = 'CACHE_LOG_DRIVER';
    public const CACHE_LOG_LOG       = 'CACHE_LOG_LOG';
    public const CACHE_LOG_PREFIX    = 'CACHE_LOG_PREFIX';

    public const CLIENT_DEFAULT  = 'CLIENT_DEFAULT';
    public const CLIENT_ADAPTERS = 'CLIENT_ADAPTERS';
    public const CLIENT_DRIVERS  = 'CLIENT_DRIVERS';
    public const CLIENT_CLIENTS  = 'CLIENT_CLIENTS';

    public const CONSOLE_PROVIDERS                   = 'CONSOLE_PROVIDERS';
    public const CONSOLE_DEV_PROVIDERS               = 'CONSOLE_DEV_PROVIDERS';
    public const CONSOLE_QUIET                       = 'CONSOLE_QUIET';
    public const CONSOLE_USE_ANNOTATIONS             = 'CONSOLE_USE_ANNOTATIONS';
    public const CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY = 'CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY';
    public const CONSOLE_HANDLERS                    = 'CONSOLE_HANDLERS';
    public const CONSOLE_FILE_PATH                   = 'CONSOLE_FILE_PATH';
    public const CONSOLE_CACHE_FILE_PATH             = 'CONSOLE_CACHE_FILE_PATH';
    public const CONSOLE_USE_CACHE_FILE              = 'CONSOLE_USE_CACHE_FILE';

    public const CONTAINER_PROVIDERS                   = 'CONTAINER_PROVIDERS';
    public const CONTAINER_DEV_PROVIDERS               = 'CONTAINER_DEV_PROVIDERS';
    public const CONTAINER_SETUP_FACADE                = 'CONTAINER_SETUP_FACADE';
    public const CONTAINER_USE_ANNOTATIONS             = 'CONTAINER_USE_ANNOTATIONS';
    public const CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY = 'CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY';
    public const CONTAINER_ALIASES                     = 'CONTAINER_ALIASES';
    public const CONTAINER_SERVICES                    = 'CONTAINER_SERVICES';
    public const CONTAINER_CONTEXT_SERVICES            = 'CONTAINER_CONTEXT_SERVICES';
    public const CONTAINER_FILE_PATH                   = 'CONTAINER_FILE_PATH';
    public const CONTAINER_CACHE_FILE_PATH             = 'CONTAINER_CACHE_FILE_PATH';
    public const CONTAINER_USE_CACHE_FILE              = 'CONTAINER_USE_CACHE_FILE';

    public const CRYPT_KEY             = 'CRYPT_KEY';
    public const CRYPT_KEY_PATH        = 'CRYPT_KEY_PATH';
    public const CRYPT_DEFAULT         = 'CRYPT_DEFAULT';
    public const CRYPT_ADAPTERS        = 'CRYPT_ADAPTERS';
    public const CRYPT_DRIVERS         = 'CRYPT_DRIVERS';
    public const CRYPT_CRYPTS          = 'CRYPT_CRYPTS';
    public const CRYPT_DEFAULT_ADAPTER = 'CRYPT_DEFAULT_ADAPTER';
    public const CRYPT_DEFAULT_DRIVER  = 'CRYPT_DEFAULT_DRIVER';

    public const EVENT_USE_ANNOTATIONS             = 'EVENT_USE_ANNOTATIONS';
    public const EVENT_USE_ANNOTATIONS_EXCLUSIVELY = 'EVENT_USE_ANNOTATIONS_EXCLUSIVELY';
    public const EVENT_LISTENERS                   = 'EVENT_LISTENERS';
    public const EVENT_FILE_PATH                   = 'EVENT_FILE_PATH';
    public const EVENT_CACHE_FILE_PATH             = 'EVENT_CACHE_FILE_PATH';
    public const EVENT_USE_CACHE_FILE              = 'EVENT_USE_CACHE_FILE';

    public const FILESYSTEM_DEFAULT                 = 'FILESYSTEM_DEFAULT';
    public const FILESYSTEM_ADAPTERS                = 'FILESYSTEM_ADAPTERS';
    public const FILESYSTEM_DRIVERS                 = 'FILESYSTEM_DRIVERS';
    public const FILESYSTEM_DISKS                   = 'FILESYSTEM_DISKS';
    public const FILESYSTEM_LOCAL_ADAPTER           = 'FILESYSTEM_LOCAL_ADAPTER';
    public const FILESYSTEM_LOCAL_DRIVER            = 'FILESYSTEM_LOCAL_DRIVER';
    public const FILESYSTEM_LOCAL_FLYSYSTEM_ADAPTER = 'FILESYSTEM_LOCAL_FLYSYSTEM_ADAPTER';
    public const FILESYSTEM_LOCAL_DIR               = 'FILESYSTEM_LOCAL_DIR';
    public const FILESYSTEM_S3_ADAPTER              = 'FILESYSTEM_S3_ADAPTER';
    public const FILESYSTEM_S3_DRIVER               = 'FILESYSTEM_S3_DRIVER';
    public const FILESYSTEM_S3_FLYSYSTEM_ADAPTER    = 'FILESYSTEM_S3_FLYSYSTEM_ADAPTER';
    public const FILESYSTEM_S3_KEY                  = 'FILESYSTEM_S3_KEY';
    public const FILESYSTEM_S3_SECRET               = 'FILESYSTEM_S3_SECRET';
    public const FILESYSTEM_S3_REGION               = 'FILESYSTEM_S3_REGION';
    public const FILESYSTEM_S3_VERSION              = 'FILESYSTEM_S3_VERSION';
    public const FILESYSTEM_S3_BUCKET               = 'FILESYSTEM_S3_BUCKET';
    public const FILESYSTEM_S3_PREFIX               = 'FILESYSTEM_S3_PREFIX';
    public const FILESYSTEM_S3_OPTIONS              = 'FILESYSTEM_S3_OPTIONS';

    public const LOG_NAME      = 'LOG_NAME';
    public const LOG_FILE_PATH = 'LOG_FILE_PATH';
    public const LOG_DEFAULT   = 'LOG_DEFAULT';
    public const LOG_ADAPTERS  = 'LOG_ADAPTERS';
    public const LOG_DRIVERS   = 'LOG_DRIVERS';
    public const LOG_LOGGERS   = 'LOG_LOGGERS';

    public const MAIL_FROM_ADDRESS          = 'MAIL_FROM_ADDRESS';
    public const MAIL_FROM_NAME             = 'MAIL_FROM_NAME';
    public const MAIL_DEFAULT               = 'MAIL_DEFAULT';
    public const MAIL_ADAPTERS              = 'MAIL_ADAPTERS';
    public const MAIL_DRIVERS               = 'MAIL_DRIVERS';
    public const MAIL_MAILERS               = 'MAIL_MAILERS';
    public const MAIL_DEFAULT_MESSAGE       = 'MAIL_DEFAULT_MESSAGE';
    public const MAIL_MESSAGE_ADAPTERS      = 'MAIL_MESSAGE_ADAPTERS';
    public const MAIL_MESSAGES              = 'MAIL_MESSAGES';
    public const MAIL_LOG_DRIVER            = 'MAIL_LOG_DRIVER';
    public const MAIL_LOG_ADAPTER           = 'MAIL_LOG_ADAPTER';
    public const MAIL_LOG_LOGGER            = 'MAIL_LOG_LOGGER';
    public const MAIL_NULL_ADAPTER          = 'MAIL_NULL_ADAPTER';
    public const MAIL_NULL_DRIVER           = 'MAIL_NULL_DRIVER';
    public const MAIL_PHP_MAILER_ADAPTER    = 'MAIL_PHP_MAILER_ADAPTER';
    public const MAIL_PHP_MAILER_DRIVER     = 'MAIL_PHP_MAILER_DRIVER';
    public const MAIL_PHP_MAILER_HOST       = 'MAIL_PHP_MAILER_HOST';
    public const MAIL_PHP_MAILER_PORT       = 'MAIL_PHP_MAILER_PORT';
    public const MAIL_PHP_MAILER_ENCRYPTION = 'MAIL_PHP_MAILER_ENCRYPTION';
    public const MAIL_PHP_MAILER_USERNAME   = 'MAIL_PHP_MAILER_USERNAME';
    public const MAIL_PHP_MAILER_PASSWORD   = 'MAIL_PHP_MAILER_PASSWORD';
    public const MAIL_MAILGUN_ADAPTER       = 'MAIL_MAILGUN_ADAPTER';
    public const MAIL_MAILGUN_DRIVER        = 'MAIL_MAILGUN_DRIVER';
    public const MAIL_MAILGUN_DOMAIN        = 'MAIL_MAILGUN_DOMAIN';
    public const MAIL_MAILGUN_API_KEY       = 'MAIL_MAILGUN_API_KEY';

    public const NOTIFICATION_NOTIFICATIONS = 'NOTIFICATION_NOTIFICATIONS';

    public const ORM_DEFAULT             = 'ORM_DEFAULT';
    public const ORM_ADAPTER             = 'ORM_ADAPTER';
    public const ORM_DRIVER              = 'ORM_DRIVER';
    public const ORM_QUERY               = 'ORM_QUERY';
    public const ORM_QUERY_BUILDER       = 'ORM_QUERY_BUILDER';
    public const ORM_PERSISTER           = 'ORM_PERSISTER';
    public const ORM_RETRIEVER           = 'ORM_RETRIEVER';
    public const ORM_REPOSITORY          = 'ORM_REPOSITORY';
    public const ORM_CONNECTIONS         = 'ORM_CONNECTIONS';
    public const ORM_MIGRATIONS          = 'ORM_MIGRATIONS';
    public const ORM_MYSQL_ADAPTER       = 'ORM_MYSQL_ADAPTER';
    public const ORM_MYSQL_QUERY         = 'ORM_MYSQL_QUERY';
    public const ORM_MYSQL_QUERY_BUILDER = 'ORM_MYSQL_QUERY_BUILDER';
    public const ORM_MYSQL_PERSISTER     = 'ORM_MYSQL_PERSISTER';
    public const ORM_MYSQL_RETRIEVER     = 'ORM_MYSQL_RETRIEVER';
    public const ORM_MYSQL_DRIVER        = 'ORM_MYSQL_DRIVER';
    public const ORM_MYSQL_REPOSITORY    = 'ORM_MYSQL_REPOSITORY';
    public const ORM_MYSQL_PDO           = 'ORM_MYSQL_PDO';
    public const ORM_MYSQL_HOST          = 'ORM_MYSQL_HOST';
    public const ORM_MYSQL_PORT          = 'ORM_MYSQL_PORT';
    public const ORM_MYSQL_DB            = 'ORM_MYSQL_DB';
    public const ORM_MYSQL_CHARSET       = 'ORM_MYSQL_CHARSET';
    public const ORM_MYSQL_USER          = 'ORM_MYSQL_USER';
    public const ORM_MYSQL_PASSWORD      = 'ORM_MYSQL_PASSWORD';
    public const ORM_MYSQL_STRICT        = 'ORM_MYSQL_STRICT';
    public const ORM_MYSQL_ENGINE        = 'ORM_MYSQL_ENGINE';
    public const ORM_MYSQL_OPTIONS       = 'ORM_MYSQL_OPTIONS';
    public const ORM_PGSQL_ADAPTER       = 'ORM_PGSQL_ADAPTER';
    public const ORM_PGSQL_QUERY         = 'ORM_PGSQL_QUERY';
    public const ORM_PGSQL_QUERY_BUILDER = 'ORM_PGSQL_QUERY_BUILDER';
    public const ORM_PGSQL_PERSISTER     = 'ORM_PGSQL_PERSISTER';
    public const ORM_PGSQL_RETRIEVER     = 'ORM_PGSQL_RETRIEVER';
    public const ORM_PGSQL_DRIVER        = 'ORM_PGSQL_DRIVER';
    public const ORM_PGSQL_REPOSITORY    = 'ORM_PGSQL_REPOSITORY';
    public const ORM_PGSQL_PDO           = 'ORM_PGSQL_PDO';
    public const ORM_PGSQL_HOST          = 'ORM_PGSQL_HOST';
    public const ORM_PGSQL_PORT          = 'ORM_PGSQL_PORT';
    public const ORM_PGSQL_DB            = 'ORM_PGSQL_DB';
    public const ORM_PGSQL_USER          = 'ORM_PGSQL_USER';
    public const ORM_PGSQL_PASSWORD      = 'ORM_PGSQL_PASSWORD';
    public const ORM_PGSQL_CHARSET       = 'ORM_PGSQL_CHARSET';
    public const ORM_PGSQL_OPTIONS       = 'ORM_PGSQL_OPTIONS';
    public const ORM_PGSQL_SSL_MODE      = 'ORM_PGSQL_SSL_MODE';
    public const ORM_PGSQL_SSL_CERT      = 'ORM_PGSQL_SSL_CERT';
    public const ORM_PGSQL_SSL_KEY       = 'ORM_PGSQL_SSL_KEY';
    public const ORM_PGSQL_SSL_ROOT_CERT = 'ORM_PGSQL_SSL_ROOT_CERT';
    public const ORM_PGSQL_SCHEMA        = 'ORM_PGSQL_SCHEMA';

    public const PATH_PATTERNS = 'PATH_PATTERNS';

    public const ROUTING_USE_TRAILING_SLASH          = 'ROUTING_USE_TRAILING_SLASH';
    public const ROUTING_USE_ABSOLUTE_URLS           = 'ROUTING_USE_ABSOLUTE_URLS';
    public const ROUTING_MIDDLEWARE                  = 'ROUTING_MIDDLEWARE';
    public const ROUTING_MIDDLEWARE_GROUPS           = 'ROUTING_MIDDLEWARE_GROUPS';
    public const ROUTING_HTTP_EXCEPTION              = 'ROUTING_HTTP_EXCEPTION';
    public const ROUTING_USE_ANNOTATIONS             = 'ROUTING_USE_ANNOTATIONS';
    public const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = 'ROUTING_USE_ANNOTATIONS_EXCLUSIVELY';
    public const ROUTING_CONTROLLERS                 = 'ROUTING_CONTROLLERS';
    public const ROUTING_FILE_PATH                   = 'ROUTING_FILE_PATH';
    public const ROUTING_CACHE_FILE_PATH             = 'ROUTING_CACHE_FILE_PATH';
    public const ROUTING_USE_CACHE_FILE              = 'ROUTING_USE_CACHE_FILE';

    public const SESSION_ID               = 'SESSION_ID';
    public const SESSION_NAME             = 'SESSION_NAME';
    public const SESSION_ADAPTER          = 'SESSION_ADAPTER';
    public const SESSION_DRIVER           = 'SESSION_DRIVER';
    public const SESSION_COOKIE_LIFETIME  = 'SESSION_COOKIE_LIFETIME';
    public const SESSION_COOKIE_PATH      = 'SESSION_COOKIE_PATH';
    public const SESSION_COOKIE_DOMAIN    = 'SESSION_COOKIE_DOMAIN';
    public const SESSION_COOKIE_SECURE    = 'SESSION_COOKIE_SECURE';
    public const SESSION_COOKIE_HTTP_ONLY = 'SESSION_COOKIE_HTTP_ONLY';
    public const SESSION_COOKIE_SAME_SITE = 'SESSION_COOKIE_SAME_SITE';
    public const SESSION_DEFAULT          = 'SESSION_DEFAULT';
    public const SESSION_ADAPTERS         = 'SESSION_ADAPTERS';
    public const SESSION_DRIVERS          = 'SESSION_DRIVERS';
    public const SESSION_SESSIONS         = 'SESSION_SESSIONS';

    public const SMS_DEFAULT          = 'SMS_DEFAULT';
    public const SMS_ADAPTERS         = 'SMS_ADAPTERS';
    public const SMS_DRIVERS          = 'SMS_DRIVERS';
    public const SMS_MESSENGERS       = 'SMS_MESSENGERS';
    public const SMS_DEFAULT_MESSAGE  = 'SMS_DEFAULT_MESSAGE';
    public const SMS_MESSAGE_ADAPTERS = 'SMS_MESSAGE_ADAPTERS';
    public const SMS_MESSAGES         = 'SMS_MESSAGES';
    public const SMS_FROM_NAME        = 'SMS_FROM_NAME';
    public const SMS_LOG_ADAPTER      = 'SMS_LOG_ADAPTER';
    public const SMS_LOG_DRIVER       = 'SMS_LOG_DRIVER';
    public const SMS_LOG_LOGGER       = 'SMS_LOG_LOGGER';
    public const SMS_NEXMO_ADAPTER    = 'SMS_NEXMO_ADAPTER';
    public const SMS_NEXMO_DRIVER     = 'SMS_NEXMO_DRIVER';
    public const SMS_NEXMO_USERNAME   = 'SMS_NEXMO_USERNAME';
    public const SMS_NEXMO_PASSWORD   = 'SMS_NEXMO_PASSWORD';
    public const SMS_NULL_ADAPTER     = 'SMS_NULL_ADAPTER';
    public const SMS_NULL_DRIVER      = 'SMS_NULL_DRIVER';

    public const STORAGE_UPLOADS_DIR = 'STORAGE_UPLOADS_DIR';
    public const STORAGE_LOGS_DIR    = 'STORAGE_LOGS_DIR';

    public const VALIDATION_RULE      = 'VALIDATION_RULE';
    public const VALIDATION_RULES     = 'VALIDATION_RULES';
    public const VALIDATION_RULES_MAP = 'VALIDATION_RULES_MAP';

    public const VIEW_DIR                 = 'VIEW_DIR';
    public const VIEW_ENGINE              = 'VIEW_ENGINE';
    public const VIEW_ENGINES             = 'VIEW_ENGINES';
    public const VIEW_PATHS               = 'VIEW_PATHS';
    public const VIEW_DISKS               = 'VIEW_DISKS';
    public const VIEW_PHP_FILE_EXTENSION  = 'VIEW_PHP_FILE_EXTENSION';
    public const VIEW_ORKA_FILE_EXTENSION = 'VIEW_ORKA_FILE_EXTENSION';
    public const VIEW_TWIG_COMPILED_DIR   = 'VIEW_TWIG_COMPILED_DIR';
    public const VIEW_TWIG_EXTENSIONS     = 'VIEW_TWIG_EXTENSIONS';
}
