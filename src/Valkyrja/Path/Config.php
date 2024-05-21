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

namespace Valkyrja\Path;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\Constant\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        CKP::PATTERNS => EnvKey::PATH_PATTERNS,
    ];

    /**
     * The patterns.
     *
     * @var array
     */
    public array $patterns;
}
