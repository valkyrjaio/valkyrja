<?php

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../../../bootstrap.php';

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Type\Support\Vlid;

assert(VlidVersion::V1 === Vlid::VERSION);
