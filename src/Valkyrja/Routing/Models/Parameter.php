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

use BackedEnum;
use Valkyrja\Model\Models\Model;
use Valkyrja\ORM\Entity;
use Valkyrja\Routing\Enums\CastType;
use Valkyrja\Support\Type\Cls;

use function is_string;

/**
 * Class Parameter.
 *
 * @author Melech Mizrachi
 */
class Parameter extends Model
{
    /**
     * @inheritDoc
     */
    protected static bool $shouldSetOriginalProperties = false;

    /**
     * Parameter constructor.
     *
     * @param string|null                   $name                [optional] The name
     * @param string|null                   $regex               [optional] The regex
     * @param CastType|null                 $type                [optional] The cast type
     * @param class-string<Entity>|null     $entity              [optional] The entity class name
     * @param string|null                   $entityColumn        [optional] The entity column
     * @param array|null                    $entityRelationships [optional] The entity relationships to get
     * @param class-string<BackedEnum>|null $enum                [optional] The enum type
     * @param bool                          $isOptional          [optional] Whether this parameter is optional
     * @param bool                          $shouldCapture       [optional] Whether this parameter should be captured
     * @param mixed                         $default             [optional] The default value for this parameter
     */
    public function __construct(
        protected string|null $name = null,
        protected string|null $regex = null,
        protected CastType|null $type = null,
        protected string|null $entity = null,
        protected string|null $entityColumn = null,
        protected array|null $entityRelationships = null,
        protected string|null $enum = null,
        protected bool $isOptional = false,
        protected bool $shouldCapture = true,
        protected mixed $default = null,
    ) {
    }

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
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
        return $this->regex;
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
     * @return CastType|null
     */
    public function getType(): ?CastType
    {
        return $this->type;
    }

    /**
     * Set the type.
     *
     * @param CastType|string|null $type The type
     *
     * @return static
     */
    public function setType(CastType|string $type = null): self
    {
        $this->type = is_string($type)
            ? CastType::from($type)
            : $type;

        return $this;
    }

    /**
     * Get the entity class.
     *
     * @return class-string<Entity>|null
     */
    public function getEntity(): ?string
    {
        return $this->entity;
    }

    /**
     * Set the entity class name.
     *
     * @param class-string<Entity>|null $entity The entity class name
     *
     * @return static
     */
    public function setEntity(string $entity = null): self
    {
        if ($entity !== null) {
            Cls::validateInherits($entity, Entity::class);
        }

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
        return $this->entityColumn;
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
        if ($entityColumn !== null) {
            Cls::validateHasProperty($this->entity ?? '', $entityColumn);
        }

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
        return $this->entityRelationships;
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
     * Get the enum class name.
     *
     * @return class-string<BackedEnum>|null
     */
    public function getEnum(): ?string
    {
        return $this->enum;
    }

    /**
     * Set the enum class name.
     *
     * @param class-string<BackedEnum>|null $enum The enum class name
     *
     * @return static
     */
    public function setEnum(string $enum = null): self
    {
        if ($enum !== null) {
            Cls::validateInherits($enum, BackedEnum::class);
        }

        $this->enum = $enum;

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

    /**
     * Get the default value.
     *
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * Set the default value.
     *
     * @return mixed
     */
    public function setDefault(mixed $default): self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function __allProperties(bool $includeHidden = false): array
    {
        return $this->__allPropertiesIncludingHidden();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->__allPropertiesIncludingHidden();
    }
}
