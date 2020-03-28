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

namespace Valkyrja\View\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Enum\Enums\Enum;
use Valkyrja\View\Engines\PHPEngine;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
{
    public const ENGINE  = CKP::PHP;
    public const ENGINES = [
        CKP::PHP => PHPEngine::class,
    ];
}
