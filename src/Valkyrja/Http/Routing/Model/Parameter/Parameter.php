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

namespace Valkyrja\Http\Routing\Model\Parameter;

use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Type\Contract\Type;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Model\Model;

use function is_array;

/**
 * Class Parameter.
 *
 * @author Melech Mizrachi
 */
class Parameter extends Model
{
    /** @var string */
    protected const string DEFAULT_NAME = 'param';

    /** @var string */
    protected const string DEFAULT_REGEX = Regex::ANY;

    /**
     * @inheritDoc
     */
    protected static bool $shouldSetOriginalProperties = false;

    protected string $name = self::DEFAULT_NAME;

    protected string $regex = self::DEFAULT_REGEX;

    /**
     * Parameter constructor.
     *
     * @param string|null $name          [optional] The name
     * @param string|null $regex         [optional] The regex
     * @param Cast|null   $cast          [optional] The casting if any
     * @param bool        $isOptional    [optional] Whether this parameter is optional
     * @param bool        $shouldCapture [optional] Whether this parameter should be captured
     * @param mixed       $default       [optional] The default value for this parameter
     */
    public function __construct(
        string|null $name = null,
        string|null $regex = null,
        protected Cast|null $cast = null,
        protected bool $isOptional = false,
        protected bool $shouldCapture = true,
        protected mixed $default = null,
    ) {
        $this->name  = $name ?? static::DEFAULT_NAME;
        $this->regex = $regex ?? static::DEFAULT_REGEX;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string
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
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return static
     */
    public function setRegex(string $regex): static
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get the cast.
     *
     * @return Cast|null
     */
    public function getCast(): Cast|null
    {
        return $this->cast;
    }

    /**
     * Set the cast.
     *
     * @param Cast|array{type: class-string<Type>, isArray?: bool, convert?: bool, column?: string, relationships?: string[]}|null $cast The cast
     *
     * @return static
     */
    public function setCast(Cast|array|null $cast = null): static
    {
        if (is_array($cast)) {
            $type          = $cast['type'];
            $isArray       = $cast['isArray'] ?? false;
            $convert       = $cast['convert'] ?? true;
            $column        = $cast['column'] ?? null;
            $relationships = $cast['relationships'] ?? null;

            $cast = ($column !== null)
                ? new EntityCast(
                    $type,
                    column: $column,
                    relationships: $relationships,
                    convert: $convert,
                    isArray: $isArray
                )
                : new Cast(
                    $type,
                    $convert,
                    $isArray
                );
        }

        $this->cast = $cast;

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
    public function setIsOptional(bool $isOptional): static
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
    public function setShouldCapture(bool $shouldCapture): static
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
     * @param mixed $default The default value
     *
     * @return static
     */
    public function setDefault(mixed $default = null): static
    {
        $this->default = $default;

        return $this;
    }
}
