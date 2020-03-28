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
    protected ?string $type = null;

    /**
     * The annotation properties (within parenthesis).
     *
     * @var array|null
     */
    protected ?array $properties = null;

    /**
     * Get the type.
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the type.
     *
     * @param string $type The type
     *
     * @return static
     */
    public function setType(string $type = null): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the annotation properties (within parentheses).
     *
     * @return array
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * Set the annotation properties (within parentheses).
     *
     * @param array $properties The annotation arguments
     *
     * @return static
     */
    public function setProperties(array $properties = null): self
    {
        $this->properties = $properties;

        return $this;
    }
}
