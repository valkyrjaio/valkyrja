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

use Twig\Extension\ExtensionInterface;
use Valkyrja\Support\Directory;
use Valkyrja\View\Constant\ConfigName;
use Valkyrja\View\Constant\EnvName;
use Valkyrja\View\Engine\TwigEngine;

/**
 * Class TwigConfiguration.
 *
 * @author Melech Mizrachi
 */
class TwigConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ENGINE         => EnvName::TWIG_ENGINE,
        ConfigName::FILE_EXTENSION => EnvName::TWIG_FILE_EXTENSION,
        ConfigName::DIR            => EnvName::TWIG_DIR,
        ConfigName::PATHS          => EnvName::TWIG_PATHS,
        ConfigName::EXTENSIONS     => EnvName::TWIG_EXTENSIONS,
        ConfigName::COMPILED_DIR   => EnvName::TWIG_COMPILED_DIR,
    ];

    /**
     * @param class-string<ExtensionInterface>[] $extensions The twig extensions
     */
    public function __construct(
        public string $compiledDir = '',
        public array $extensions = [],
    ) {
        parent::__construct(
            engine: TwigEngine::class,
            fileExtension: '.twig',
        );
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
        if ($this->compiledDir === '') {
            $this->compiledDir = Directory::storagePath('views');
        }
    }
}
