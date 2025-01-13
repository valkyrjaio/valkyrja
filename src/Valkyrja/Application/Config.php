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

namespace Valkyrja\Application;

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Exception\Contract\ErrorHandler;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        CKP::ENV           => EnvKey::APP_ENV,
        CKP::DEBUG         => EnvKey::APP_DEBUG,
        CKP::URL           => EnvKey::APP_URL,
        CKP::TIMEZONE      => EnvKey::APP_TIMEZONE,
        CKP::VERSION       => EnvKey::APP_VERSION,
        CKP::KEY           => EnvKey::APP_KEY,
        CKP::ERROR_HANDLER => EnvKey::APP_ERROR_HANDLER,
        CKP::PROVIDERS     => EnvKey::APP_PROVIDERS,
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
     * The error handler class.
     *
     * @var class-string<ErrorHandler>
     */
    public string $errorHandler;

    /**
     * Array of config providers.
     *  NOTE: Provider::deferred() is disregarded.
     *
     * @var Provider[]|string[]
     */
    public array $providers;
}
