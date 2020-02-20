<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Annotation;

use Valkyrja\Annotation\Annotation;
use Valkyrja\Console\Command as CommandModel;

/**
 * Interface Command.
 *
 * @author Melech Mizrachi
 */
interface Command extends Annotation, CommandModel
{
}
