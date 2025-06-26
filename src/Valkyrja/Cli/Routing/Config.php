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

namespace Valkyrja\Cli\Routing;

use Valkyrja\Application\Command\CacheCommand;
use Valkyrja\Application\Command\ClearCacheCommand;
use Valkyrja\Cli\Routing\Command\HelpCommand;
use Valkyrja\Cli\Routing\Command\ListBashCommand;
use Valkyrja\Cli\Routing\Command\ListCommand;
use Valkyrja\Cli\Routing\Command\VersionCommand;
use Valkyrja\Cli\Routing\Config\Cache;
use Valkyrja\Cli\Routing\Constant\ConfigName;
use Valkyrja\Cli\Routing\Constant\EnvName;
use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Http\Routing\Command\ListCommand as HttpListCommand;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::CONTROLLERS => EnvName::CONTROLLERS,
    ];

    /**
     * @param class-string[] $controllers The attributed controller classes
     */
    public function __construct(
        public array $controllers = [],
        public Cache|null $cache = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
        $this->controllers = [
            ListCommand::class,
            ListBashCommand::class,
            VersionCommand::class,
            HelpCommand::class,
            CacheCommand::class,
            ClearCacheCommand::class,
            HttpListCommand::class,
            ...$this->controllers,
        ];
    }
}
