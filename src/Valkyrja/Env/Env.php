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

namespace Valkyrja\Env;

/**
 * Class Env.
 *
 * @author Melech Mizrachi
 */
class Env
{
    /*
     * Config env variables.
     */
    // public const CONFIG_CLASS                 = null;
    // public const CONFIG_PROVIDERS             = null;
    // public const CONFIG_FILE_PATH             = null;
    // public const CONFIG_CACHE_FILE_PATH       = null;
    // public const CONFIG_CACHE_ALLOWED_CLASSES = null;
    // public const CONFIG_USE_CACHE_FILE        = null;

    /*
     * Application env variables.
     */
    // public const APP_ENV                  = null;
    // public const APP_DEBUG                = null;
    // public const APP_URL                  = null;
    // public const APP_TIMEZONE             = null;
    // public const APP_VERSION              = null;
    // public const APP_KEY                  = null;
    // public const APP_HTTP_EXCEPTION_CLASS = null;
    //
    // public const APP_CONTAINER         = null;
    // public const APP_DISPATCHER        = null;
    // public const APP_EVENTS            = null;
    // public const APP_EXCEPTION_HANDLER = null;

    /*
     * Annotation env variables.
     */
    // public const ANNOTATIONS_ENABLED   = null;
    // public const ANNOTATIONS_CACHE_DIR = null;
    // public const ANNOTATIONS_MAP       = null;

    /*
     * Auth env variables.
     */
    // public const AUTH_ADAPTER                = null;
    // public const AUTH_USER_ENTITY            = null;
    // public const AUTH_REPOSITORY             = null;
    // public const AUTH_ADAPTERS               = null;
    // public const AUTH_ALWAYS_AUTHENTICATE    = null;
    // public const AUTH_KEEP_USER_FRESH        = null;
    // public const AUTH_AUTHENTICATE_ROUTE     = null;
    // public const AUTH_PASSWORD_CONFIRM_ROUTE = null;

    /*
     * Console env variables.
     */
    // public const CONSOLE_PROVIDERS                   = null;
    // public const CONSOLE_DEV_PROVIDERS               = null;
    // public const CONSOLE_QUIET                       = null;
    // public const CONSOLE_USE_ANNOTATIONS             = null;
    // public const CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY = null;
    // public const CONSOLE_HANDLERS                    = null;
    // public const CONSOLE_FILE_PATH                   = null;
    // public const CONSOLE_CACHE_FILE_PATH             = null;
    // public const CONSOLE_USE_CACHE_FILE              = null;

    /*
     * Container env variables.
     */
    // public const CONTAINER_PROVIDERS                   = null;
    // public const CONTAINER_DEV_PROVIDERS               = null;
    // public const CONTAINER_USE_ANNOTATIONS             = null;
    // public const CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY = null;
    // public const CONTAINER_ALIASES                     = null;
    // public const CONTAINER_SERVICES                    = null;
    // public const CONTAINER_CONTEXT_SERVICES            = null;
    // public const CONTAINER_FILE_PATH                   = null;
    // public const CONTAINER_CACHE_FILE_PATH             = null;
    // public const CONTAINER_USE_CACHE_FILE              = null;

    /*
     * Crypt env variables.
     */
    // public const CRYPT_KEY      = null;
    // public const CRYPT_KEY_PATH = null;

    /*
     * Database env variables.
     */
    // public const DB_CONNECTION = null;
    // public const DB_HOST       = null;
    // public const DB_PORT       = null;
    // public const DB_DATABASE   = null;
    // public const DB_USERNAME   = null;
    // public const DB_PASSWORD   = null;
    // public const DB_SOCKET     = null;
    // public const DB_CHARSET    = null;
    // public const DB_COLLATION  = null;
    // public const DB_PREFIX     = null;
    // public const DB_STRICT     = null;
    // public const DB_ENGINE     = null;
    // public const DB_SCHEMA     = null;
    // public const DB_SSL_MODE   = null;

    /*
     * Events env variables.
     */
    // public const EVENT_USE_ANNOTATIONS             = null;
    // public const EVENT_USE_ANNOTATIONS_EXCLUSIVELY = null;
    // public const EVENT_LISTENERS                   = null;
    // public const EVENT_FILE_PATH                   = null;
    // public const EVENT_CACHE_FILE_PATH             = null;
    // public const EVENT_USE_CACHE_FILE              = null;

    /*
     * Filesystem env variables.
     */
    // public const FILESYSTEM_DEFAULT    = null;
    // public const FILESYSTEM_LOCAL_DIR  = null;
    // public const FILESYSTEM_S3_KEY     = null;
    // public const FILESYSTEM_S3_SECRET  = null;
    // public const FILESYSTEM_S3_REGION  = null;
    // public const FILESYSTEM_S3_VERSION = null;
    // public const FILESYSTEM_S3_BUCKET  = null;
    // public const FILESYSTEM_S3_DIR     = null;
    // public const FILESYSTEM_S3_OPTIONS = null;

    /*
     * Logger env variables.
     */
    // public const LOG_NAME      = null;
    // public const LOG_FILE_PATH = null;

    /*
     * Mail env variables.
     */
    // public const MAIL_HOST         = null;
    // public const MAIL_PORT         = null;
    // public const MAIL_FROM_ADDRESS = null;
    // public const MAIL_FROM_NAME    = null;
    // public const MAIL_ENCRYPTION   = null;
    // public const MAIL_USERNAME     = null;
    // public const MAIL_PASSWORD     = null;

    /**
     * Path env variables.
     */
    // public const PATH_PATTERNS = null;

    /*
     * Routing env variables.
     */
    // public const ROUTING_TRAILING_SLASH              = null;
    // public const ROUTING_USE_ABSOLUTE_URLS           = null;
    // public const ROUTING_MIDDLEWARE                  = null;
    // public const ROUTING_MIDDLEWARE_GROUPS           = null;
    // public const ROUTING_USE_ANNOTATIONS             = null;
    // public const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = null;
    // public const ROUTING_CONTROLLERS                 = null;
    // public const ROUTING_FILE_PATH                   = null;
    // public const ROUTING_CACHE_FILE_PATH             = null;
    // public const ROUTING_USE_CACHE_FILE              = null;

    /*
     * Session env variables.
     */
    // public const SESSION_ID   = null;
    // public const SESSION_NAME = null;

    /*
     * Storage env variables.
     */
    // public const STORAGE_UPLOADS_DIR = null;
    // public const STORAGE_LOGS_DIR    = null;

    /*
     * View env variables.
     */
    // public const VIEW_DIR     = null;
    // public const VIEW_ENGINE  = null;
    // public const VIEW_ENGINES = null;
    // public const VIEW_PATHS   = null;

    /*
     * Twig views env variables.
     */
    // public const TWIG_FILE_EXTENSION = null;
    // public const TWIG_DIR            = null;
    // public const TWIG_DIR_NS         = null;
    // public const TWIG_DIRS           = null;
    // public const TWIG_COMPILED_DIR   = null;
    // public const TWIG_EXTENSIONS     = null;
}
