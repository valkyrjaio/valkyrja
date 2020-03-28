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
    public function getType(): ?string;

    /**
     * Set the type.
     *
     * @param string $annotationType The type
     *
     * @return static
     */
    public function setType(string $annotationType = null): self;

    /**
     * Get the annotation properties (within parentheses).
     *
     * @return array
     */
    public function getProperties(): ?array;

    /**
     * Set the annotation properties (within parentheses).
     *
     * @param array $properties The annotation arguments
     *
     * @return static
     */
    public function setProperties(array $properties = null): self;
}
