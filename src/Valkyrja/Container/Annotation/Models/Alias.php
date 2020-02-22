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

namespace Valkyrja\Container\Annotation\Models;

use Valkyrja\Annotation\Models\Annotation;
use Valkyrja\Container\Annotation\Service\Alias as Contract;

/**
 * Class ServiceAlias.
 *
 * @author Melech Mizrachi
 */
class Alias extends Annotation implements Contract
{
}
