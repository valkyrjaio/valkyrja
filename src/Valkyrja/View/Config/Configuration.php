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

namespace Valkyrja\View\Config;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Support\Directory;
use Valkyrja\View\Engine\Contract\Engine;

/**
 * Abstract Class Configuration.
 *
 * @author Melech Mizrachi
 */
abstract class Configuration extends ParentConfig
{
    /**
     * @param class-string<Engine>  $engine The engine class name
     * @param array<string, string> $paths  The paths
     */
    public function __construct(
        public string $engine,
        public string $fileExtension,
        public string $dir = '',
        public array $paths = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
        if ($this->dir === '') {
            $this->dir = Directory::resourcesPath('views');
        }
    }
}
