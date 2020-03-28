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

namespace Valkyrja\ORM\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Enum\Enums\Enum;
use Valkyrja\ORM\Adapters\PDOAdapter;

/**
 * Enum Config.
 */
final class Config extends Enum
{
    public const ADAPTERS = [
        CKP::PDO => PDOAdapter::class,
    ];
}
