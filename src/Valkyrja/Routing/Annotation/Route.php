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

namespace Valkyrja\Routing\Annotation;

use Valkyrja\Annotation\Annotation;
use Valkyrja\Routing\Route as Contract;

/**
 * Interface Route.
 *
 * @author Melech Mizrachi
 */
interface Route extends Annotation, Contract
{
}
