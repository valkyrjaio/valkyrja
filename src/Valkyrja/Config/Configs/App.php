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

namespace Valkyrja\Config\Configs;

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Container\Enums\Provider;
use Valkyrja\Config\Models\Config as Model;

/**
 * Class App.
 *
 * @author Melech Mizrachi
 */
class App extends Model
{
    public string $env              = 'production';
    public bool   $debug            = false;
    public string $url              = 'localhost';
    public string $timezone         = 'UTC';
    public string $version          = Application::VERSION;
    public string $key              = 'some_secret_app_key';
    public string $httpException    = Provider::HTTP_EXCEPTION;
    public string $container        = Provider::CONTAINER;
    public string $dispatcher       = Provider::DISPATCHER;
    public string $events           = Provider::EVENTS;
    public string $exceptionHandler = Provider::EXCEPTION_HANDLER;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->env              = (string) env(EnvKey::APP_ENV, $this->env);
        $this->debug            = (bool) env(EnvKey::APP_DEBUG, $this->debug);
        $this->url              = (string) env(EnvKey::APP_URL, $this->url);
        $this->timezone         = (string) env(EnvKey::APP_TIMEZONE, $this->timezone);
        $this->version          = (string) env(EnvKey::APP_VERSION, $this->version);
        $this->key              = (string) env(EnvKey::APP_KEY, $this->key);
        $this->httpException    = (string) env(EnvKey::APP_HTTP_EXCEPTION_CLASS, $this->httpException);
        $this->container        = (string) env(EnvKey::APP_CONTAINER, $this->container);
        $this->dispatcher       = (string) env(EnvKey::APP_DISPATCHER, $this->dispatcher);
        $this->events           = (string) env(EnvKey::APP_EVENTS, $this->events);
        $this->exceptionHandler = (string) env(EnvKey::APP_EXCEPTION_HANDLER, $this->exceptionHandler);
    }
}
