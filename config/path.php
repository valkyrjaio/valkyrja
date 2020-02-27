<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Path Configuration
 *-------------------------------------------------------------------------
 *
 * //
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Path\Enums\Config;

return [
    /*
     *-------------------------------------------------------------------------
     * Path Patterns
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PATTERNS => env(EnvKey::PATH_PATTERNS, Config::PATTERNS),
];
