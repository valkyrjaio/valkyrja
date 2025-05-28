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

namespace Valkyrja\Config\Config;

use Valkyrja\Application\Config as App;
use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\Support\Provider;
use Valkyrja\Console\Config as Console;
use Valkyrja\Event\Config as Event;
use Valkyrja\Notification\Config as Notification;
use Valkyrja\Orm\Config as ORM;

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
        CKP::PROVIDERS       => EnvKey::CONFIG_PROVIDERS,
        CKP::CACHE_FILE_PATH => EnvKey::CONFIG_CACHE_FILE_PATH,
        CKP::USE_CACHE       => EnvKey::CONFIG_USE_CACHE_FILE,
    ];

    /**
     * The application module config.
     *
     * @var App
     */
    public App $app;

    /**
     * The console module config.
     *
     * @var Console
     */
    public Console $console;

    /**
     * The event module config.
     *
     * @var Event
     */
    public Event $event;

    /**
     * The notification module config.
     *
     * @var Notification
     */
    public Notification $notification;

    /**
     * The ORM module config.
     *
     * @var ORM
     */
    public ORM $orm;

    /**
     * Array of config providers.
     *  NOTE: Provider::deferred() is disregarded.
     *
     * @var class-string<Provider>[]
     */
    public array $providers;

    /**
     * The cache file path.
     *
     * @var string
     */
    public string $cacheFilePath;

    /**
     * Whether to use cache.
     *
     * @var bool
     */
    public bool $useCache;
}
