<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config;

/**
 * Class Env
 *
 * @package Valkyrja\Config
 */
class Env
{
    const APP_ENV = 'production';
    const APP_DEBUG = false;
    const APP_URL = 'localhost';
    const APP_TIMEZONE = 'UTC';
    const APP_VERSION = '1 (ALPHA)';

    const STORAGE_UPLOADS_DIR = null;
    const STORAGE_LOGS_DIR = null;

    const ROUTING_USE_ARRAY_ARGS = false;

    const VIEWS_DIR = null;

    const VIEWS_TWIG_ENABLED = false;
    const VIEWS_TWIG_DIR = null;
    const VIEWS_TWIG_COMPILED_DIR = null;
    const VIEWS_TWIG_EXTENSIONS = [];
}
