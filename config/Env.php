<?php

namespace config;

class Env
{
    const APP_ENV = 'local';
    const APP_DEBUG = true;
    const APP_URL = 'app.dev';
    const APP_TIMEZONE = 'UTC';
    const APP_VERSION = '1 (ALPHA)';

    const STORAGE_UPLOADS_DIR = null;
    const STORAGE_LOGS_DIR = null;

    const VIEWS_DIR = null;

    const VIEWS_TWIG_ENABLED = true;
    const VIEWS_TWIG_DIR = null;
    const VIEWS_TWIG_COMPILED_DIR = null;
    const VIEWS_TWIG_EXTENSIONS = [];
}
