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

use Valkyrja\Support\Directory;
use Valkyrja\View\Constant\ConfigName;
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
        ConfigName::ENGINE         => 'VIEW_TWIG_ENGINE',
        ConfigName::FILE_EXTENSION => 'VIEW_TWIG_FILE_EXTENSION',
        'extensions'               => 'VIEW_TWIG_EXTENSIONS',
    ];

    /**
     * @param string[] $extensions
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
