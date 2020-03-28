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

namespace Valkyrja\Config\Configs;

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Container\Enums\Provider;
use Valkyrja\Config\Models\Model;

use function Valkyrja\env;

/**
 * Class App.
 *
 * @author Melech Mizrachi
 */
class App extends Model
{
    /**
     * The environment name.
     *
     * @var string
     */
    public string $env;

    /**
     * Flag to enable debug.
     *
     * @var bool
     */
    public bool $debug;

    /**
     * The url.
     *
     * @var string
     */
    public string $url;

    /**
     * The timezone.
     *
     * @var string
     */
    public string $timezone;

    /**
     * The version.
     *
     * @var string
     */
    public string $version;

    /**
     * The key.
     *
     * @var string
     */
    public string $key;

    /**
     * The http exception class.
     *
     * @var string
     */
    public string $httpException;

    /**
     * The container class.
     *
     * @var string
     */
    public string $container;

    /**
     * The dispatcher class.
     *
     * @var string
     */
    public string $dispatcher;

    /**
     * The events manager class.
     *
     * @var string
     */
    public string $events;

    /**
     * The exception handler class.
     *
     * @var string
     */
    public string $exceptionHandler;

    /**
     * App constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setEnv();
        $this->setDebug();
        $this->setUrl();
        $this->setTimezone();
        $this->setVersion();
        $this->setKey();
        $this->setHttpException();
        $this->setContainer();
        $this->setDispatcher();
        $this->setEvents();
        $this->setExceptionHandler();
    }

    /**
     * Set the environment name.
     *
     * @param string $env [optional] The environment name
     *
     * @return void
     */
    protected function setEnv(string $env = 'production'): void
    {
        $this->env = (string) env(EnvKey::APP_ENV, $env);
    }

    /**
     * Set the debug flag.
     *
     * @param bool $debug [optional] The debug flag
     *
     * @return void
     */
    protected function setDebug(bool $debug = false): void
    {
        $this->debug = (bool) env(EnvKey::APP_DEBUG, $debug);
    }

    /**
     * Set the url.
     *
     * @param string $url [optional] The url
     *
     * @return void
     */
    protected function setUrl(string $url = 'localhost'): void
    {
        $this->url = (string) env(EnvKey::APP_URL, $url);
    }

    /**
     * Set the timezone.
     *
     * @param string $timezone [optional] The timezone
     *
     * @return void
     */
    protected function setTimezone(string $timezone = 'UTC'): void
    {
        $this->timezone = (string) env(EnvKey::APP_TIMEZONE, $timezone);
    }

    /**
     * Set the version.
     *
     * @param string $version [optional] The verion
     *
     * @return void
     */
    protected function setVersion(string $version = Application::VERSION): void
    {
        $this->version = (string) env(EnvKey::APP_VERSION, $version);
    }

    /**
     * Set the key.
     *
     * @param string $key [optional] The key
     *
     * @return void
     */
    protected function setKey(string $key = 'some_secret_app_key'): void
    {
        $this->key = (string) env(EnvKey::APP_KEY, $key);
    }

    /**
     * Set the http exception class.
     *
     * @param string $httpException [optional] The http exception class
     *
     * @return void
     */
    protected function setHttpException(string $httpException = Provider::HTTP_EXCEPTION): void
    {
        $this->httpException = (string) env(EnvKey::APP_HTTP_EXCEPTION_CLASS, $httpException);
    }

    /**
     * Set the container class.
     *
     * @param string $container [optional] The container class
     *
     * @return void
     */
    protected function setContainer(string $container = Provider::CONTAINER): void
    {
        $this->container = (string) env(EnvKey::APP_CONTAINER, $container);
    }

    /**
     * Set the dispatcher class.
     *
     * @param string $dispatcher [optional] The dispatcher class
     *
     * @return void
     */
    protected function setDispatcher(string $dispatcher = Provider::DISPATCHER): void
    {
        $this->dispatcher = (string) env(EnvKey::APP_DISPATCHER, $dispatcher);
    }

    /**
     * Set the events manager class.
     *
     * @param string $events [optional] The events manager class
     *
     * @return void
     */
    protected function setEvents(string $events = Provider::EVENTS): void
    {
        $this->events = (string) env(EnvKey::APP_EVENTS, $events);
    }

    /**
     * Set the exception handler class.
     *
     * @param string $exceptionHandler [optional] The exception handler class
     *
     * @return void
     */
    protected function setExceptionHandler(string $exceptionHandler = Provider::EXCEPTION_HANDLER): void
    {
        $this->exceptionHandler = (string) env(EnvKey::APP_EXCEPTION_HANDLER, $exceptionHandler);
    }
}
