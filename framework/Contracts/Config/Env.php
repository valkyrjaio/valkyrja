<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Config;

/**
 * Interface Env
 *
 * @package Valkyrja\Contracts\Config
 *
 * @author  Melech Mizrachi
 */
interface Env
{
    /**
     * Application env variables.
     */
    public const APP_ENV      = null;
    public const APP_DEBUG    = null;
    public const APP_URL      = null;
    public const APP_TIMEZONE = null;
    public const APP_VERSION  = null;

    public const APP_CONTAINER = null;
    public const APP_EVENTS    = null;

    /**
     * Annotation env variables.
     */
    public const ANNOTATIONS_ENABLED   = null;
    public const ANNOTATIONS_CACHE_DIR = null;
    public const ANNOTATIONS_MAP       = null;

    /**
     * Console env variables.
     */
    public const CONSOLE_USE_ANNOTATIONS             = null;
    public const CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY = null;
    public const CONSOLE_HANDLERS                    = null;
    public const CONSOLE_FILE_PATH                   = null;
    public const CONSOLE_CACHE_FILE_PATH             = null;
    public const CONSOLE_USE_CACHE_FILE              = null;

    /**
     * Container env variables.
     */
    public const CONTAINER_PROVIDERS                   = null;
    public const CONTAINER_DEV_PROVIDERS               = null;
    public const CONTAINER_USE_ANNOTATIONS             = null;
    public const CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY = null;
    public const CONTAINER_SERVICES                    = null;
    public const CONTAINER_CONTEXT_SERVICES            = null;
    public const CONTAINER_FILE_PATH                   = null;
    public const CONTAINER_CACHE_FILE_PATH             = null;
    public const CONTAINER_USE_CACHE_FILE              = null;

    /**
     * Events env variables.
     */
    public const EVENTS_USE_ANNOTATIONS             = null;
    public const EVENTS_USE_ANNOTATIONS_EXCLUSIVELY = null;
    public const EVENTS_CLASSES                     = null;
    public const EVENTS_FILE_PATH                   = null;
    public const EVENTS_CACHE_FILE_PATH             = null;
    public const EVENTS_USE_CACHE_FILE              = null;

    /**
     * Logger env variables.
     */
    public const LOGGER_NAME      = null;
    public const LOGGER_FILE_PATH = null;

    /**
     * Routing env variables.
     */
    public const ROUTING_TRAILING_SLASH              = null;
    public const ROUTING_USE_ANNOTATIONS             = null;
    public const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = null;
    public const ROUTING_CONTROLLERS                 = null;
    public const ROUTING_FILE_PATH                   = null;
    public const ROUTING_CACHE_FILE_PATH             = null;
    public const ROUTING_USE_CACHE_FILE              = null;

    /**
     * Storage env variables.
     */
    public const STORAGE_UPLOADS_DIR = null;
    public const STORAGE_LOGS_DIR    = null;

    /**
     * Views env variables.
     */
    public const VIEWS_DIR = null;

    /**
     * Twig views env variables.
     */
    public const VIEWS_TWIG_ENABLED      = null;
    public const VIEWS_TWIG_DIR          = null;
    public const VIEWS_TWIG_COMPILED_DIR = null;
    public const VIEWS_TWIG_EXTENSIONS   = null;
}
