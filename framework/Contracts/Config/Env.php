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
    const APP_ENV = null;
    const APP_DEBUG = null;
    const APP_URL = null;
    const APP_TIMEZONE = null;
    const APP_VERSION = null;

    const ANNOTATIONS_ENABLED = null;
    const ANNOTATIONS_CACHE_DIR = null;

    const ROUTING_TRAILING_SLASH = null;
    const ROUTING_ALLOW_WITH_TRAILING_SLASH = null;
    const ROUTING_USE_ANNOTATIONS = null;
    const ROUTING_USE_ANNOTATIONS_EXCLUSIVELY = null;
    const ROUTING_CONTROLLERS = null;
    const ROUTING_ROUTES_FILE = null;
    const ROUTING_ROUTES_CACHE_FILE = null;
    const ROUTING_USE_ROUTES_CACHE_FILE = null;

    const STORAGE_UPLOADS_DIR = null;
    const STORAGE_LOGS_DIR = null;

    const VIEWS_DIR = null;

    const VIEWS_TWIG_ENABLED = null;
    const VIEWS_TWIG_DIR = null;
    const VIEWS_TWIG_COMPILED_DIR = null;
    const VIEWS_TWIG_EXTENSIONS = null;
}
