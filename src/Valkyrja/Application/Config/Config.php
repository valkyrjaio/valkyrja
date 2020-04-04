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

namespace Valkyrja\Application\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::ENV,
        CKP::DEBUG,
        CKP::URL,
        CKP::TIMEZONE,
        CKP::VERSION,
        CKP::KEY,
        CKP::CONTAINER,
        CKP::DISPATCHER,
        CKP::EVENTS,
        CKP::EXCEPTION_HANDLER,
        CKP::HTTP_EXCEPTION,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::ENV               => EnvKey::APP_ENV,
        CKP::DEBUG             => EnvKey::APP_DEBUG,
        CKP::URL               => EnvKey::APP_URL,
        CKP::TIMEZONE          => EnvKey::APP_TIMEZONE,
        CKP::VERSION           => EnvKey::APP_VERSION,
        CKP::KEY               => EnvKey::APP_KEY,
        CKP::CONTAINER         => EnvKey::APP_CONTAINER,
        CKP::DISPATCHER        => EnvKey::APP_DISPATCHER,
        CKP::EVENTS            => EnvKey::APP_EVENTS,
        CKP::EXCEPTION_HANDLER => EnvKey::APP_EXCEPTION_HANDLER,
        CKP::HTTP_EXCEPTION    => EnvKey::APP_HTTP_EXCEPTION,
    ];

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
     * The http exception class.
     *
     * @var string
     */
    public string $httpException;
}
