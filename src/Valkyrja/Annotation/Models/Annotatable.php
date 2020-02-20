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

namespace Valkyrja\Annotation\Models;

/**
 * Trait Annotatable.
 *
 * @author Melech Mizrachi
 */
trait Annotatable
{
    /**
     * The type.
     *
     * @var string|null
     */
    protected ?string $annotationType = null;

    /**
     * The annotation properties (within parenthesis).
     *
     * @var array|null
     */
    protected ?array $annotationProperties = null;

    /**
     * Get the type.
     *
     * @return string
     */
    public function getAnnotationType(): ?string
    {
        return $this->annotationType;
    }

    /**
     * Set the type.
     *
     * @param string $annotationType The type
     *
     * @return $this
     */
    public function setAnnotationType(string $annotationType = null): self
    {
        $this->annotationType = $annotationType;

        return $this;
    }

    /**
     * Get the annotation properties (within parentheses).
     *
     * @return array
     */
    public function getAnnotationProperties(): ?array
    {
        return $this->annotationProperties;
    }

    /**
     * Set the annotation properties (within parentheses).
     *
     * @param array $annotationProperties The annotation arguments
     *
     * @return $this
     */
    public function setAnnotationProperties(array $annotationProperties = null): self
    {
        $this->annotationProperties = $annotationProperties;

        return $this;
    }
}
