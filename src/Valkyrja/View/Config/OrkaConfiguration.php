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

use Valkyrja\View\Constant\ConfigName;
use Valkyrja\View\Engine\OrkaEngine;

/**
 * Class OrkaConfiguration.
 *
 * @author Melech Mizrachi
 */
class OrkaConfiguration extends PhpConfiguration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ENGINE         => 'VIEW_ORKA_ENGINE',
        ConfigName::FILE_EXTENSION => 'VIEW_ORKA_FILE_EXTENSION',
        ConfigName::DIR            => 'VIEW_ORKA_DIR',
        ConfigName::PATHS          => 'VIEW_ORKA_PATHS',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->engine        = OrkaEngine::class;
        $this->fileExtension = '.orka.phtml';
    }
}
