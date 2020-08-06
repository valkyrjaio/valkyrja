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

namespace Valkyrja\Annotation;

/**
 * Interface AnnotationsParser.
 *
 * @author Melech Mizrachi
 */
interface Parser
{
    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return Annotation[]
     */
    public function getAnnotations(string $docString): array;

    /**
     * Filter a string of arguments into an key => value array.
     *
     * @param string|null $arguments The arguments
     *
     * @return array
     */
    public function getPropertiesAsArray(string $arguments = null): ?array;

    /**
     * Get the annotations regex.
     *
     * @return string
     */
    public function getRegex(): string;

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
     * @return Annotation
     */
    public function getAnnotationFromMap(string $annotationType): Annotation;
}
