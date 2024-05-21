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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\View\Config\Config as Model;
use Valkyrja\View\Constants\ConfigValue;

use function Valkyrja\env;
use function Valkyrja\resourcesPath;
use function Valkyrja\storagePath;

/**
 * Class View.
 */
class View extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->dir     = resourcesPath('views');
        $this->engines = array_merge(ConfigValue::ENGINES, []);
        $this->disks   = [
            CKP::PHP  => [
                CKP::FILE_EXTENSION => env(EnvKey::VIEW_PHP_FILE_EXTENSION, '.phtml'),
            ],
            CKP::ORKA => [
                CKP::FILE_EXTENSION => env(EnvKey::VIEW_ORKA_FILE_EXTENSION, '.orka.phtml'),
            ],
            CKP::TWIG => [
                CKP::COMPILED_DIR => env(EnvKey::VIEW_TWIG_COMPILED_DIR, storagePath('views')),
                CKP::EXTENSIONS   => env(EnvKey::VIEW_TWIG_EXTENSIONS, []),
            ],
        ];
    }
}
