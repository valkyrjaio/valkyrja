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

namespace Valkyrja\Cli\Routing\Controller;

use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;

/**
 * Abstract Class Controller.
 *
 * @author Melech Mizrachi
 */
abstract class Controller
{
    public function __construct(
        protected InputContract $input,
        protected OutputFactoryContract $outputFactory,
    ) {
    }
}
