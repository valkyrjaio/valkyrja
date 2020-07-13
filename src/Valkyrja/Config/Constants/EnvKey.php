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
    public const APP_PROVIDERS         = 'APP_PROVIDERS';

    public const ANNOTATIONS_ENABLED   = 'ANNOTATIONS_ENABLED';
    public const ANNOTATIONS_CACHE_DIR = 'ANNOTATIONS_CACHE_DIR';
    public const ANNOTATIONS_MAP       = 'ANNOTATIONS_MAP';
    public const ANNOTATIONS_ALIASES   = 'ANNOTATIONS_ALIASES';

    public const AUTH_ADAPTER                = 'AUTH_ADAPTER';
    public const AUTH_USER_ENTITY            = 'AUTH_USER_ENTITY';
    public const AUTH_REPOSITORY             = 'AUTH_REPOSITORY';
    public const AUTH_ADAPTERS               = 'AUTH_ADAPTERS';
    public const AUTH_ALWAYS_AUTHENTICATE    = 'AUTH_ALWAYS_AUTHENTICATE';
    public const AUTH_KEEP_USER_FRESH        = 'AUTH_KEEP_USER_FRESH';
    public const AUTH_AUTHENTICATE_ROUTE     = 'AUTH_AUTHENTICATE_ROUTE';
    public const AUTH_PASSWORD_CONFIRM_ROUTE = 'AUTH_PASSWORD_CONFIRM_ROUTE';

    public const CACHE_DEFAULT = 'CACHE_DEFAULT';
    public const CACHE_STORES  = 'CACHE_STORES';

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

    public const CRYPT_KEY      = 'CRYPT_KEY';
    public const CRYPT_KEY_PATH = 'CRYPT_KEY_PATH';
    public const CRYPT_ADAPTER  = 'CRYPT_ADAPTER';
    public const CRYPT_ADAPTERS = 'CRYPT_ADAPTERS';

    public const EVENT_USE_ANNOTATIONS             = 'EVENT_USE_ANNOTATIONS';
    public const EVENT_USE_ANNOTATIONS_EXCLUSIVELY = 'EVENT_USE_ANNOTATIONS_EXCLUSIVELY';
    public const EVENT_LISTENERS                   = 'EVENT_LISTENERS';
    public const EVENT_FILE_PATH                   = 'EVENT_FILE_PATH';
    public const EVENT_CACHE_FILE_PATH             = 'EVENT_CACHE_FILE_PATH';
    public const EVENT_USE_CACHE_FILE              = 'EVENT_USE_CACHE_FILE';

    public const FILESYSTEM_DEFAULT       = 'FILESYSTEM_DEFAULT';
    public const FILESYSTEM_ADAPTERS      = 'FILESYSTEM_ADAPTERS';
    public const FILESYSTEM_DISKS         = 'FILESYSTEM_DISKS';
    public const FILESYSTEM_LOCAL_DIR     = 'FILESYSTEM_LOCAL_DIR';
    public const FILESYSTEM_LOCAL_ADAPTER = 'FILESYSTEM_LOCAL_ADAPTER';
    public const FILESYSTEM_S3_KEY        = 'FILESYSTEM_S3_KEY';
    public const FILESYSTEM_S3_SECRET     = 'FILESYSTEM_S3_SECRET';
    public const FILESYSTEM_S3_REGION     = 'FILESYSTEM_S3_REGION';
    public const FILESYSTEM_S3_VERSION    = 'FILESYSTEM_S3_VERSION';
    public const FILESYSTEM_S3_BUCKET     = 'FILESYSTEM_S3_BUCKET';
    public const FILESYSTEM_S3_DIR        = 'FILESYSTEM_S3_DIR';
    public const FILESYSTEM_S3_OPTIONS    = 'FILESYSTEM_S3_OPTIONS';
    public const FILESYSTEM_S3_ADAPTER    = 'FILESYSTEM_S3_ADAPTER';

    public const LOG_NAME      = 'LOG_NAME';
    public const LOG_FILE_PATH = 'LOG_FILE_PATH';

    public const MAIL_HOST         = 'MAIL_HOST';
    public const MAIL_PORT         = 'MAIL_PORT';
    public const MAIL_FROM_ADDRESS = 'MAIL_FROM_ADDRESS';
    public const MAIL_FROM_NAME    = 'MAIL_FROM_NAME';
    public const MAIL_ENCRYPTION   = 'MAIL_ENCRYPTION';
    public const MAIL_USERNAME     = 'MAIL_USERNAME';
    public const MAIL_PASSWORD     = 'MAIL_PASSWORD';
    public const MAIL_MESSAGE      = 'MAIL_MESSAGE';
    public const MAIL_MESSAGES     = 'MAIL_MESSAGES';

    public const ORM_CONNECTION     = 'ORM_CONNECTION';
    public const ORM_ADAPTERS       = 'ORM_ADAPTERS';
    public const ORM_CONNECTIONS    = 'ORM_CONNECTIONS';
    public const ORM_REPOSITORY     = 'ORM_REPOSITORY';
    public const ORM_MYSQL_ADAPTER  = 'ORM_MYSQL_ADAPTER';
    public const ORM_MYSQL_DRIVER   = 'ORM_MYSQL_DRIVER';
    public const ORM_MYSQL_HOST     = 'ORM_MYSQL_HOST';
    public const ORM_MYSQL_PORT     = 'ORM_MYSQL_PORT';
    public const ORM_MYSQL_DB       = 'ORM_MYSQL_DB';
    public const ORM_MYSQL_CHARSET  = 'ORM_MYSQL_CHARSET';
    public const ORM_MYSQL_USERNAME = 'ORM_MYSQL_USERNAME';
    public const ORM_MYSQL_PASSWORD = 'ORM_MYSQL_PASSWORD';

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

    public const SESSION_ID   = 'SESSION_ID';
    public const SESSION_NAME = 'SESSION_NAME';

    public const SMS_USERNAME = 'SMS_USERNAME';
    public const SMS_PASSWORD = 'SMS_PASSWORD';
    public const SMS_MESSAGE  = 'SMS_MESSAGE';
    public const SMS_MESSAGES = 'SMS_MESSAGES';

    public const STORAGE_UPLOADS_DIR = 'STORAGE_UPLOADS_DIR';
    public const STORAGE_LOGS_DIR    = 'STORAGE_LOGS_DIR';

    public const VALIDATION_RULE      = 'VALIDATION_RULE';
    public const VALIDATION_RULES     = 'VALIDATION_RULES';
    public const VALIDATION_RULES_MAP = 'VALIDATION_RULES_MAP';

    public const VIEW_DIR     = 'VIEW_DIR';
    public const VIEW_ENGINE  = 'VIEW_ENGINE';
    public const VIEW_ENGINES = 'VIEW_ENGINES';
    public const VIEW_PATHS   = 'VIEW_PATHS';

    public const TWIG_FILE_EXTENSION = 'TWIG_FILE_EXTENSION';
    public const TWIG_DIR            = 'TWIG_DIR';
    public const TWIG_DIR_NS         = 'TWIG_DIR_NS';
    public const TWIG_DIRS           = 'TWIG_DIRS';
    public const TWIG_COMPILED_DIR   = 'TWIG_COMPILED_DIR';
    public const TWIG_EXTENSIONS     = 'TWIG_EXTENSIONS';
}
