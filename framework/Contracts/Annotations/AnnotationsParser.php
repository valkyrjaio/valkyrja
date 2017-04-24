<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Annotations;

use Valkyrja\Contracts\Annotations\Regex\ArgumentsRegex;
use Valkyrja\Contracts\Annotations\Regex\ClassRegex;

/**
 * Interface AnnotationsParser
 *
 * @package Valkyrja\Contracts\Annotations
 *
 * @author  Melech Mizrachi
 */
interface AnnotationsParser extends ClassRegex, ArgumentsRegex
{
    /**
     * Annotation symbol.
     *
     * @constant string
     */
    public const ANNOTATION_SYMBOL = '@';

    /**
     * Get the annotation's name.
     *
     * @return string
     */
    public function getAnnotationName(): string;

    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function getAnnotations(string $docString): array;
}
