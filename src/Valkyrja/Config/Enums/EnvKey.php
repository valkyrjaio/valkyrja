<?php

declare(strict_types=1);

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
 * Enum EnvKey.
 *
 * @author Melech Mizrachi
 */
final class EnvKey extends Enum
{
    public const CONFIG_PROVIDERS       = 'CONFIG_PROVIDERS';
    public const CONFIG_FILE_PATH       = 'CONFIG_FILE_PATH';
    public const CONFIG_CACHE_FILE_PATH = 'CONFIG_CACHE_FILE_PATH';
    public const CONFIG_USE_CACHE_FILE  = 'CONFIG_USE_CACHE_FILE';

    public const APP_ENV                  = 'APP_ENV';
    public const APP_DEBUG                = 'APP_DEBUG';
    public const APP_URL                  = 'APP_URL';
    public const APP_TIMEZONE             = 'APP_TIMEZONE';
    public const APP_VERSION              = 'APP_VERSION';
    public const APP_KEY                  = 'APP_KEY';
    public const APP_HTTP_EXCEPTION_CLASS = 'APP_HTTP_EXCEPTION_CLASS';
    public const APP_PATH_REGEX_MAP       = 'APP_PATH_REGEX_MAP';
    public const APP_CONTAINER            = 'APP_CONTAINER';
    public const APP_DISPATCHER           = 'APP_DISPATCHER';
    public const APP_EVENTS               = 'APP_EVENTS';
    public const APP_EXCEPTION_HANDLER    = 'APP_EXCEPTION_HANDLER';

    public const ANNOTATIONS_ENABLED   = 'ANNOTATIONS_ENABLED';
    public const ANNOTATIONS_CACHE_DIR = 'ANNOTATIONS_CACHE_DIR';
    public const ANNOTATIONS_MAP       = 'ANNOTATIONS_MAP';
    public const ANNOTATIONS_ALIASES   = 'ANNOTATIONS_ALIASES';

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
    public const CONTAINER_USE_ANNOTATIONS             = 'CONTAINER_USE_ANNOTATIONS';
    public const CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY = 'CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY';
    public const CONTAINER_SERVICES                    = 'CONTAINER_SERVICES';
    public const CONTAINER_CONTEXT_SERVICES            = 'CONTAINER_CONTEXT_SERVICES';
    public const CONTAINER_FILE_PATH                   = 'CONTAINER_FILE_PATH';
    public const CONTAINER_CACHE_FILE_PATH             = 'CONTAINER_CACHE_FILE_PATH';
    public const CONTAINER_USE_CACHE_FILE              = 'CONTAINER_USE_CACHE_FILE';

    public const CRYPT_KEY      = 'CRYPT_KEY';
    public const CRYPT_KEY_PATH = 'CRYPT_KEY_PATH';

    public const DB_CONNECTION = 'DB_CONNECTION';
    public const DB_HOST       = 'DB_HOST';
    public const DB_PORT       = 'DB_PORT';
    public const DB_DATABASE   = 'DB_DATABASE';
    public const DB_USERNAME   = 'DB_USERNAME';
    public const DB_PASSWORD   = 'DB_PASSWORD';
    public const DB_SOCKET     = 'DB_SOCKET';
    public const DB_CHARSET    = 'DB_CHARSET';
    public const DB_COLLATION  = 'DB_COLLATION';
    public const DB_PREFIX     = 'DB_PREFIX';
    public const DB_STRICT     = 'DB_STRICT';
    public const DB_ENGINE     = 'DB_ENGINE';
    public const DB_SCHEME     = 'DB_SCHEME';
    public const DB_SSL_MODE   = 'DB_SSL_MODE';

    public const EVENTS_USE_ANNOTATIONS             = 'EVENTS_USE_ANNOTATIONS';
    public const EVENTS_USE_ANNOTATIONS_EXCLUSIVELY = 'EVENTS_USE_ANNOTATIONS_EXCLUSIVELY';
    public const EVENTS_CLASSES                     = 'EVENTS_CLASSES';
    public const EVENTS_FILE_PATH                   = 'EVENTS_FILE_PATH';
    public const EVENTS_CACHE_FILE_PATH             = 'EVENTS_CACHE_FILE_PATH';
    public const EVENTS_USE_CACHE_FILE              = 'EVENTS_USE_CACHE_FILE';

    public const FILESYSTEM_DEFAULT    = 'FILESYSTEM_DEFAULT';
    public const FILESYSTEM_LOCAL_DIR  = 'FILESYSTEM_LOCAL_DIR';
    public const FILESYSTEM_S3_KEY     = 'FILESYSTEM_S3_KEY';
    public const FILESYSTEM_S3_SECRET  = 'FILESYSTEM_S3_SECRET';
    public const FILESYSTEM_S3_REGION  = 'FILESYSTEM_S3_REGION';
    public const FILESYSTEM_S3_VERSION = 'FILESYSTEM_S3_VERSION';
    public const FILESYSTEM_S3_BUCKET  = 'FILESYSTEM_S3_BUCKET';
    public const FILESYSTEM_S3_DIR     = 'FILESYSTEM_S3_DIR';
    public const FILESYSTEM_S3_OPTIONS = 'FILESYSTEM_S3_OPTIONS';

    public const LOGGER_NAME      = 'LOGGER_NAME';
    public const LOGGER_FILE_PATH = 'LOGGER_FILE_PATH';

    public const MAIL_HOST         = 'MAIL_HOST';
    public const MAIL_PORT         = 'MAIL_PORT';
    public const MAIL_FROM_ADDRESS = 'MAIL_FROM_ADDRESS';
    public const MAIL_FROM_NAME    = 'MAIL_FROM_NAME';
    public const MAIL_ENCRYPTION   = 'MAIL_ENCRYPTION';
    public const MAIL_USERNAME     = 'MAIL_USERNAME';
    public const MAIL_PASSWORD     = 'MAIL_PASSWORD';

    public const ROUTING_TRAILING_SLASH              = 'ROUTING_TRAILING_SLASH';
    public const ROUTING_USE_ABSOLUTE_URLS           = 'ROUTING_USE_ABSOLUTE_URLS';
    public const ROUTING_MIDDLEWARE                  = 'ROUTING_MIDDLEWARE';
    public const ROUTING_MIDDLEWARE_GROUPS           = 'ROUTING_MIDDLEWARE_GROUPS';
    public const ROUTING_USE_ANNOTATIONS             = 'ROUTING_USE_ANNOTATIONS';
    public const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = 'ROUTING_USE_ANNOTATIONS_EXCLUSIVELY';
    public const ROUTING_CONTROLLERS                 = 'ROUTING_CONTROLLERS';
    public const ROUTING_FILE_PATH                   = 'ROUTING_FILE_PATH';
    public const ROUTING_CACHE_FILE_PATH             = 'ROUTING_CACHE_FILE_PATH';
    public const ROUTING_USE_CACHE_FILE              = 'ROUTING_USE_CACHE_FILE';

    public const SESSION_ID   = 'SESSION_ID';
    public const SESSION_NAME = 'SESSION_NAME';

    public const STORAGE_UPLOADS_DIR = 'STORAGE_UPLOADS_DIR';
    public const STORAGE_LOGS_DIR    = 'STORAGE_LOGS_DIR';

    public const VIEWS_DIR   = 'VIEWS_DIR';
    public const VIEWS_PATHS = 'VIEWS_PATHS';

    public const TWIG_FILE_EXTENSION = 'TWIG_FILE_EXTENSION';
    public const TWIG_DIR            = 'TWIG_DIR';
    public const TWIG_DIR_NS         = 'TWIG_DIR_NS';
    public const TWIG_DIRS           = 'TWIG_DIRS';
    public const TWIG_COMPILED_DIR   = 'TWIG_COMPILED_DIR';
    public const TWIG_EXTENSIONS     = 'TWIG_EXTENSIONS';
}
