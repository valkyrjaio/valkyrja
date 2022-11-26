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
use Valkyrja\ORM\Entity;
use Valkyrja\Routing\Enums\CastType;
use Valkyrja\Support\Model\Classes\Model;
use Valkyrja\Support\Model\Enums\CastType as ModelCastType;
use Valkyrja\Support\Type\Cls;

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
    protected static bool $setOriginalPropertiesFromArray = false;

    /**
     * @inheritDoc
     */
    protected static array $castings = [
        'type' => [ModelCastType::enum, CastType::class],
    ];

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
     * @var CastType|null
     */
    public ?CastType $type;

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
     * The enum class name.
     *
     * @var string|null
     */
    public ?string $enum;

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
     * The default to use if this is an optional parameter.
     *
     * @var mixed|null
     */
    public mixed $default = null;

    /**
     * Parameter constructor.
     *
     * @param string|null   $name                [optional] The name
     * @param string|null   $regex               [optional] The regex
     * @param CastType|null $type                [optional] The cast type
     * @param string|null   $entity              [optional] The entity class name
     * @param string|null   $entityColumn        [optional] The entity column
     * @param array|null    $entityRelationships [optional] The entity relationships to get
     * @param string|null   $enum                [optional] The enum type
     * @param bool|null     $isOptional          [optional] Whether this parameter is optional
     * @param bool|null     $shouldCapture       [optional] Whether this parameter should be captured
     * @param mixed         $default             [optional] The default value for this parameter
     */
    public function __construct(
        string $name = null,
        string $regex = null,
        CastType $type = null,
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        string $enum = null,
        bool $isOptional = null,
        bool $shouldCapture = null,
        mixed $default = null,
    ) {
        if ($name) {
            $this->setName($name);
        }

        if ($regex) {
            $this->setRegex($regex);
        }

        if ($type) {
            $this->setType($type);
        }

        if ($entity) {
            $this->setEntity($entity);
        }

        if ($entityColumn) {
            $this->setEntityColumn($entityColumn);
        }

        if ($entityRelationships) {
            $this->setEntityRelationships($entityRelationships);
        }

        if ($enum) {
            $this->setEnum($enum);
        }

        if ($isOptional) {
            $this->setIsOptional($isOptional);
        }

        if ($shouldCapture) {
            $this->setShouldCapture($shouldCapture);
        }

        if ($default) {
            $this->setDefault($default);
        }
    }

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
     * @return CastType|null
     */
    public function getType(): ?CastType
    {
        return $this->type ?? null;
    }

    /**
     * Set the type.
     *
     * @param CastType|null $type The type
     *
     * @return static
     */
    public function setType(CastType $type = null): self
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
        if ($entityColumn !== null) {
            Cls::validateHasProperty($this->entity, $entityColumn);
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
     * Get the enum class name.
     *
     * @return BackedEnum|string|null
     */
    public function getEnum(): ?string
    {
        return $this->enum;
    }

    /**
     * Set the enum class name.
     *
     * @param string|null $enum The enum class name
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
}
