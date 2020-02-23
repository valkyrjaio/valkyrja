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

namespace Valkyrja\Console\Annotation;

use ReflectionException;
use Valkyrja\Annotation\Annotator;

/**
 * Class CommandAnnotations.
 *
 * @author Melech Mizrachi
 */
interface CommandAnnotator extends Annotator
{
    /**
     * Get the commands.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return \Valkyrja\Console\Command[]
     */
    public function getCommands(string ...$classes): array;
}