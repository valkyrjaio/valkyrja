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

require_once __DIR__ . '/../../../../../bootstrap.php';

use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Support\Vlid;

assert(Version::V1 === Vlid::VERSION);
