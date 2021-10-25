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

namespace Valkyrja\Routing\Models;

use Valkyrja\Support\Model\Classes\Model;

/**
 * Class Parameter.
 *
 * @author Melech Mizrachi
 */
class Parameter extends Model
{
    /**
     * The name.
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * The regex.
     *
     * @var string|null
     */
    public ?string $regex;

    /**
     * The parameter type to cast as.
     *
     * @var string|null
     */
    public ?string $type;

    /**
     * The entity class name.
     *
     * @var string|null
     */
    public ?string $entity;

    /**
     * The entity column to query on.
     *
     * @var string|null
     */
    public ?string $entityColumn;

    /**
     * The entity relationships.
     *
     * @var string[]|null
     */
    public ?array $entityRelationships;

    /**
     * Whether this parameter is optional.
     *
     * @var bool
     */
    public bool $isOptional = false;

    /**
     * Whether this parameter should be captured and passed to the action.
     *
     * @var bool
     */
    public bool $shouldCapture = true;

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * Set the name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the regex.
     *
     * @return string|null
     */
    public function getRegex(): ?string
    {
        return $this->regex ?? null;
    }

    /**
     * Set the regex.
     *
     * @param string|null $regex The regex
     *
     * @return static
     */
    public function setRegex(string $regex = null): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get the type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    /**
     * Set the type.
     *
     * @param string|null $type The type
     *
     * @return static
     */
    public function setType(string $type = null): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the entity class.
     *
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return $this->entity ?? null;
    }

    /**
     * Set the entity class name.
     *
     * @param string|null $entity The entity class name
     *
     * @return static
     */
    public function setEntity(string $entity = null): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the entity column associated with the parameter value.
     *
     * @return string|null
     */
    public function getEntityColumn(): ?string
    {
        return $this->entityColumn ?? null;
    }

    /**
     * Set the entity column associated with the parameter value.
     *
     * @param string|null $entityColumn The entity column associated with the parameter value
     *
     * @return static
     */
    public function setEntityColumn(string $entityColumn = null): self
    {
        $this->entityColumn = $entityColumn;

        return $this;
    }

    /**
     * Get the entity relationships.
     *
     * @return string[]|null
     */
    public function getEntityRelationships(): ?array
    {
        return $this->entityRelationships ?? null;
    }

    /**
     * Set the entity relationships.
     *
     * @param string[]|null $entityRelationships The entity relationships
     *
     * @return static
     */
    public function setEntityRelationships(array $entityRelationships = null): self
    {
        $this->entityRelationships = $entityRelationships;

        return $this;
    }

    /**
     * Get whether this parameter is optional.
     *
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->isOptional;
    }

    /**
     * Set whether this parameter is optional.
     *
     * @param bool $isOptional Whether this parameter is optional
     *
     * @return static
     */
    public function setIsOptional(bool $isOptional): self
    {
        $this->isOptional = $isOptional;

        return $this;
    }

    /**
     * Get whether this parameter should be captured.
     *
     * @return bool
     */
    public function shouldCapture(): bool
    {
        return $this->shouldCapture;
    }

    /**
     * Set whether this parameter should be captured.
     *
     * @param bool $shouldCapture Whether this parameter should be captured
     *
     * @return static
     */
    public function setShouldCapture(bool $shouldCapture): self
    {
        $this->shouldCapture = $shouldCapture;

        return $this;
    }
}
