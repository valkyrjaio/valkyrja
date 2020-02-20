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

namespace Valkyrja\Annotation;

use Valkyrja\Dispatcher\Dispatch;

/**
 * Interface Annotation.
 *
 * @author Melech Mizrachi
 */
interface Annotation extends Dispatch
{
    /**
     * Get the type.
     *
     * @return string
     */
    public function getAnnotationType(): ?string;

    /**
     * Set the type.
     *
     * @param string $annotationType The type
     *
     * @return $this
     */
    public function setAnnotationType(string $annotationType = null): self;

    /**
     * Get the annotation properties (within parentheses).
     *
     * @return array
     */
    public function getAnnotationProperties(): ?array;

    /**
     * Set the annotation properties (within parentheses).
     *
     * @param array $annotationProperties The annotation arguments
     *
     * @return $this
     */
    public function setAnnotationProperties(array $annotationProperties = null): self;
}
