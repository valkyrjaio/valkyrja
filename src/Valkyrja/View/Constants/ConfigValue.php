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

namespace Valkyrja\View\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\View\Engines\PHPEngine;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DIR     = '';
    public const ENGINE  = CKP::PHP;
    public const ENGINES = [
        CKP::PHP => PHPEngine::class,
    ];
    public const PATHS   = [];

    public static array $defaults = [
        CKP::DIR     => self::DIR,
        CKP::ENGINE  => self::ENGINE,
        CKP::ENGINES => self::ENGINES,
        CKP::PATHS   => self::PATHS,
    ];
}
