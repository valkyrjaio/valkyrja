<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Console\Annotations;

use Valkyrja\Contracts\Annotations\Annotations;

/**
 * Class CommandAnnotations
 *
 * @package Valkyrja\Contracts\Console\Annotations
 *
 * @author  Melech Mizrachi
 */
interface CommandAnnotations extends Annotations
{
    /**
     * Get the commands.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Console\Command[]
     *
     * @throws \ReflectionException
     */
    public function getCommands(string ...$classes): array;
}
