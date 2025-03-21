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
use Valkyrja\View\Engine\PhpEngine;

/**
 * Class PhpConfiguration.
 *
 * @author Melech Mizrachi
 */
class PhpConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ENGINE         => 'VIEW_PHP_ENGINE',
        ConfigName::FILE_EXTENSION => 'VIEW_PHP_FILE_EXTENSION',
    ];

    public function __construct()
    {
        parent::__construct(
            engine: PhpEngine::class,
            fileExtension: '.php',
        );
    }
}
