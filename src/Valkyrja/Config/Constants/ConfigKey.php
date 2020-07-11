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
 * Constant ConfigKey.
 *
 * @author Melech Mizrachi
 */
final class ConfigKey
{
    public const CONFIG_PROVIDERS       = 'providers';
    public const CONFIG_FILE_PATH       = 'filePath';
    public const CONFIG_CACHE_FILE_PATH = 'cacheFilePath';
    public const CONFIG_USE_CACHE_FILE  = 'useCache';

    public const CACHE_CONTAINER = 'cache.container';
    public const CACHE_EVENTS    = 'cache.events';
    public const CACHE_CONSOLE   = 'cache.console';
    public const CACHE_ROUTING   = 'cache.routing';

    public const API_JSON_MODEL      = 'api.jsonModel';
    public const API_JSON_DATA_MODEL = 'api.jsonDataModel';

    public const APP_ENV                  = 'app.env';
    public const APP_DEBUG                = 'app.debug';
    public const APP_URL                  = 'app.url';
    public const APP_TIMEZONE             = 'app.timezone';
    public const APP_VERSION              = 'app.version';
    public const APP_KEY                  = 'app.key';
    public const APP_HTTP_EXCEPTION_CLASS = 'app.httpExceptionClass';
    public const APP_CONTAINER            = 'app.container';
    public const APP_DISPATCHER           = 'app.dispatcher';
    public const APP_EVENTS               = 'app.events';
    public const APP_EXCEPTION_HANDLER    = 'app.exceptionHandler';

    public const ANNOTATIONS_ENABLED   = 'annotation.enabled';
    public const ANNOTATIONS_CACHE_DIR = 'annotation.cacheDir';
    public const ANNOTATIONS_MAP       = 'annotation.map';
    public const ANNOTATIONS_ALIASES   = 'annotation.aliases';

    public const AUTH_ADAPTER                = 'auth.adapter';
    public const AUTH_USER_ENTITY            = 'auth.userEntity';
    public const AUTH_REPOSITORY             = 'auth.repository';
    public const AUTH_ADAPTERS               = 'auth.adapters';
    public const AUTH_ALWAYS_AUTHENTICATE    = 'auth.alwaysAuthenticate';
    public const AUTH_KEEP_USER_FRESH        = 'auth.keepUserFresh';
    public const AUTH_AUTHENTICATE_ROUTE     = 'auth.authenticateRoute';
    public const AUTH_PASSWORD_CONFIRM_ROUTE = 'auth.confirmPasswordRoute';

    public const CONSOLE_PROVIDERS                   = 'console.providers';
    public const CONSOLE_DEV_PROVIDERS               = 'console.devProviders';
    public const CONSOLE_QUIET                       = 'console.quiet';
    public const CONSOLE_USE_ANNOTATIONS             = 'console.useAnnotations';
    public const CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY = 'console.useAnnotationsExclusively';
    public const CONSOLE_HANDLERS                    = 'console.handlers';
    public const CONSOLE_FILE_PATH                   = 'console.filePath';
    public const CONSOLE_CACHE_FILE_PATH             = 'console.cacheFilePath';
    public const CONSOLE_USE_CACHE_FILE              = 'console.useCache';

    public const CONTAINER_PROVIDERS                   = 'container.providers';
    public const CONTAINER_DEV_PROVIDERS               = 'container.devProviders';
    public const CONTAINER_SETUP_FACADE                = 'container.setupFacade';
    public const CONTAINER_USE_ANNOTATIONS             = 'container.useAnnotations';
    public const CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY = 'container.useAnnotationsExclusively';
    public const CONTAINER_SERVICES                    = 'container.services';
    public const CONTAINER_CONTEXT_SERVICES            = 'container.contextServices';
    public const CONTAINER_FILE_PATH                   = 'container.filePath';
    public const CONTAINER_CACHE_FILE_PATH             = 'container.cacheFilePath';
    public const CONTAINER_USE_CACHE_FILE              = 'container.useCache';

    public const CRYPT_KEY      = 'crypt.key';
    public const CRYPT_KEY_PATH = 'crypt.keyPath';

    public const DB_CONNECTION  = 'orm.connection';
    public const DB_ADAPTERS    = 'orm.adapters';
    public const DB_CONNECTIONS = 'orm.connections';
    public const DB_REPOSITORY  = 'orm.repository';

    public const EVENTS_USE_ANNOTATIONS             = 'event.useAnnotations';
    public const EVENTS_USE_ANNOTATIONS_EXCLUSIVELY = 'event.useAnnotationsExclusively';
    public const EVENTS_CLASSES                     = 'event.classes';
    public const EVENTS_FILE_PATH                   = 'event.filePath';
    public const EVENTS_CACHE_FILE_PATH             = 'event.cacheFilePath';
    public const EVENTS_USE_CACHE_FILE              = 'event.useCache';

    public const FILESYSTEM_DEFAULT       = 'filesystem.default';
    public const FILESYSTEM_ADAPTERS      = 'filesystem.adapters';
    public const FILESYSTEM_DISKS         = 'filesystem.disks';
    public const FILESYSTEM_LOCAL_DIR     = 'filesystem.disks.local.dir';
    public const FILESYSTEM_LOCAL_ADAPTER = 'filesystem.disks.local.adapter';
    public const FILESYSTEM_S3_KEY        = 'filesystem.disks.s3.key';
    public const FILESYSTEM_S3_SECRET     = 'filesystem.disks.s3.secret';
    public const FILESYSTEM_S3_REGION     = 'filesystem.disks.s3.region';
    public const FILESYSTEM_S3_VERSION    = 'filesystem.disks.s3.version';
    public const FILESYSTEM_S3_BUCKET     = 'filesystem.disks.s3.bucket';
    public const FILESYSTEM_S3_DIR        = 'filesystem.disks.s3.dir';
    public const FILESYSTEM_S3_OPTIONS    = 'filesystem.disks.s3.options';
    public const FILESYSTEM_S3_ADAPTER    = 'filesystem.disks.s3.adapter';

    public const LOG_NAME      = 'log.name';
    public const LOG_FILE_PATH = 'log.filePath';

    public const MAIL_HOST         = 'mail.host';
    public const MAIL_PORT         = 'mail.port';
    public const MAIL_FROM_ADDRESS = 'mail.from.address';
    public const MAIL_FROM_NAME    = 'mail.from.name';
    public const MAIL_ENCRYPTION   = 'mail.encryption';
    public const MAIL_USERNAME     = 'mail.username';
    public const MAIL_PASSWORD     = 'mail.password';
    public const MAIL_MESSAGE      = 'mail.message';
    public const MAIL_MESSAGES     = 'mail.messages';

    public const PATH_PATTERNS = 'path.patterns';

    public const ROUTING_TRAILING_SLASH              = 'routing.trailingSlash';
    public const ROUTING_USE_ABSOLUTE_URLS           = 'routing.useAbsoluteUrls';
    public const ROUTING_MIDDLEWARE                  = 'routing.middleware';
    public const ROUTING_MIDDLEWARE_GROUPS           = 'routing.middlewareGroups';
    public const ROUTING_USE_ANNOTATIONS             = 'routing.useAnnotations';
    public const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = 'routing.useAnnotationsExclusively';
    public const ROUTING_CONTROLLERS                 = 'routing.controllers';
    public const ROUTING_FILE_PATH                   = 'routing.filePath';
    public const ROUTING_CACHE_FILE_PATH             = 'routing.cacheFilePath';
    public const ROUTING_USE_CACHE_FILE              = 'routing.useCache';

    public const SESSION_ID   = 'session.id';
    public const SESSION_NAME = 'session.name';

    public const SMS_USERNAME = 'sms.username';
    public const SMS_PASSWORD = 'sms.password';
    public const SMS_MESSAGE  = 'sms.message';
    public const SMS_MESSAGES = 'sms.messages';

    public const STORAGE_UPLOADS_DIR = 'storage.uploadsDir';
    public const STORAGE_LOGS_DIR    = 'storage.logsDir';

    public const VALIDATION_RULE      = 'validation.rule';
    public const VALIDATION_RULES     = 'validation.rules';
    public const VALIDATION_RULES_MAP = 'validation.rulesMap';

    public const VIEW_DIR     = 'view.dir';
    public const VIEW_ENGINE  = 'view.engine';
    public const VIEW_ENGINES = 'view.engines';
    public const VIEW_PATHS   = 'view.paths';

    public const TWIG_FILE_EXTENSION = 'twig.fileExtension';
    public const TWIG_DIR            = 'twig.dir';
    public const TWIG_DIR_NS         = 'twig.dirNs';
    public const TWIG_DIRS           = 'twig.dirs';
    public const TWIG_COMPILED_DIR   = 'twig.compiledDir';
    public const TWIG_EXTENSIONS     = 'twig.extensions';
}
