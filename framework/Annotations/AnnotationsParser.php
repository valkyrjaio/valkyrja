<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations;

use Valkyrja\Contracts\Annotations\AnnotationsParser as AnnotationsParserContract;

/**
 * Class Annotations
 *
 * @package Valkyrja\Annotations
 *
 * @author  Melech Mizrachi
 */
abstract class AnnotationsParser implements AnnotationsParserContract
{
    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    abstract public function getAnnotations(string $docString): array;
}