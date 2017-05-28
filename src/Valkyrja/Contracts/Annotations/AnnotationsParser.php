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
use Valkyrja\Contracts\Annotations\Regex\LineRegex;
use Valkyrja\Contracts\Application;

/**
 * Interface AnnotationsParser.
 *
 * @author Melech Mizrachi
 */
interface AnnotationsParser extends ClassRegex, LineRegex, ArgumentsRegex
{
    /**
     * Annotation symbol.
     *
     * @constant string
     */
    public const ANNOTATION_SYMBOL = '@';

    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function getAnnotations(string $docString): array;

    /**
     * Filter a string of arguments into an key => value array.
     *
     * @param string $arguments The arguments
     *
     * @return array
     */
    public function getArguments(string $arguments = null):? array;

    /**
     * Get the annotations regex.
     *
     * @return string
     */
    public function getRegex(): string;

    /**
     * Get the arguments regex.
     *
     * @return string
     */
    public function getArgumentsRegex(): string;

    /**
     * Get the annotations map.
     *
     * @return array
     */
    public function getAnnotationsMap(): array;

    /**
     * Get an annotation model from the annotations map.
     *
     * @param string $annotationType The annotation type
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation
     */
    public function getAnnotationFromMap(string $annotationType): Annotation;
}
