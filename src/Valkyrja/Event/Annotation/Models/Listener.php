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

namespace Valkyrja\Event\Annotation\Models;

use Valkyrja\Annotation\Models\Annotation;
use Valkyrja\Event\Listener as Contract;
use Valkyrja\Event\Models\Listenable;

/**
 * Class Listener.
 *
 * @author Melech Mizrachi
 */
class Listener extends Annotation implements Contract
{
    use Listenable;
}
