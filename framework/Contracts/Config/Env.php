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

    /**
     * Annotation env variables.
     */
    public const ANNOTATIONS_ENABLED   = null;
    public const ANNOTATIONS_CACHE_DIR = null;

    /**
     * Logger env variables.
     */
    public const LOGGER_NAME      = null;
    public const LOGGER_FILE_PATH = null;

    /**
     * Routing env variables.
     */
    public const ROUTING_TRAILING_SLASH              = null;
    public const ROUTING_ALLOW_WITH_TRAILING_SLASH   = null;
    public const ROUTING_USE_ANNOTATIONS             = null;
    public const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = null;
    public const ROUTING_CONTROLLERS                 = null;
    public const ROUTING_ROUTES_FILE                 = null;
    public const ROUTING_ROUTES_CACHE_FILE           = null;
    public const ROUTING_USE_ROUTES_CACHE_FILE       = null;

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
