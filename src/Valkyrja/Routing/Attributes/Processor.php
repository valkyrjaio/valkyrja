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

namespace Valkyrja\Routing\Annotators;

use Valkyrja\Reflection\Reflector;

/**
 * Class Processor.
 *
 * @author Melech Mizrachi
 */
class Processor
{
    public function __construct(
        protected Reflector $reflector
    ) {
        $routes = $this->reflector->getClassReflection();
    }
}
