<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Annotations;

use Valkyrja\Annotations\Annotatable;
use Valkyrja\Console\Command as ConsoleCommand;
use Valkyrja\Contracts\Annotations\Annotation;

/**
 * Class Command.
 *
 * @author Melech Mizrachi
 */
class Command extends ConsoleCommand implements Annotation
{
    use Annotatable;
}
