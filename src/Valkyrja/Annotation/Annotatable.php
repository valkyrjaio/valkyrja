<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotation;

/**
 * Class Annotatable.
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
     * The id.
     *
     * @var string|null
     */
    protected ?string $id = null;

    /**
     * The name.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The class.
     *
     * @var string|null
     */
    protected ?string $class = null;

    /**
     * The property.
     *
     * @var string|null
     */
    protected ?string $property = null;

    /**
     * The method.
     *
     * @var string|null
     */
    protected ?string $method = null;

    /**
     * Whether the property or method is static.
     *
     * @var bool
     */
    protected bool $static = false;

    /**
     * The function.
     *
     * @var string|null
     */
    protected ?string $function = null;

    /**
     * The matches.
     *
     * @var array|null
     */
    protected ?array $matches = null;

    /**
     * The dependencies.
     *
     * @var array|null
     */
    protected ?array $dependencies = null;

    /**
     * The arguments.
     *
     * @var array|null
     */
    protected ?array $arguments = null;

    /**
     * The arguments.
     *
     * @var array|null
     */
    protected ?array $annotationArguments = null;

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
     * @return void
     */
    public function setAnnotationType(string $annotationType = null): void
    {
        $this->annotationType = $annotationType;
    }

    /**
     * Get the id.
     *
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param string $id The id
     *
     * @return void
     */
    public function setId(string $id = null): void
    {
        $this->id = $id;
    }

    /**
     * Get the name.
     *
     * @return string
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
     * @return void
     */
    public function setName(string $name = null): void
    {
        $this->name = $name;
    }

    /**
     * Get the class.
     *
     * @return string
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * Set the class.
     *
     * @param string $class The class
     *
     * @return void
     */
    public function setClass(string $class = null): void
    {
        $this->class = $class;
    }

    /**
     * Get the property.
     *
     * @return string
     */
    public function getProperty(): ?string
    {
        return $this->property;
    }

    /**
     * Set the property.
     *
     * @param string $property The property
     *
     * @return void
     */
    public function setProperty(string $property = null): void
    {
        $this->property = $property;
    }

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * Set the method.
     *
     * @param string $method The method
     *
     * @return void
     */
    public function setMethod(string $method = null): void
    {
        $this->method = $method;
    }

    /**
     * Get whether the member is static.
     *
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->static;
    }

    /**
     * Set whether the member is static.
     *
     * @param bool $static Whether the member is static
     *
     * @return void
     */
    public function setStatic(bool $static = true): void
    {
        $this->static = $static;
    }

    /**
     * @return string
     */
    public function getFunction(): ?string
    {
        return $this->function;
    }

    /**
     * Set the function.
     *
     * @param string $function The function
     *
     * @return void
     */
    public function setFunction(string $function = null): void
    {
        $this->function = $function;
    }

    /**
     * Get the matches.
     *
     * @return array
     */
    public function getMatches(): ?array
    {
        return $this->matches;
    }

    /**
     * Set the matches.
     *
     * @param array $matches The matches
     *
     * @return void
     */
    public function setMatches(array $matches = null): void
    {
        $this->matches = $matches;
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    /**
     * Set the arguments.
     *
     * @param array $arguments The arguments
     *
     * @return void
     */
    public function setArguments(array $arguments = null): void
    {
        $this->arguments = $arguments;
    }

    /**
     * Get the dependencies.
     *
     * @return array
     */
    public function getDependencies(): ?array
    {
        return $this->dependencies;
    }

    /**
     * Set the dependencies.
     *
     * @param array $dependencies The dependencies
     *
     * @return void
     */
    public function setDependencies(array $dependencies = null): void
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Get the annotation arguments (within parentheses).
     *
     * @return array
     */
    public function getAnnotationArguments(): ?array
    {
        return $this->annotationArguments;
    }

    /**
     * Set the annotation arguments (within parentheses).
     *
     * @param array $annotationArguments The annotation arguments
     *
     * @return void
     */
    public function setAnnotationArguments(array $annotationArguments = null): void
    {
        $this->annotationArguments = $annotationArguments;
    }
}
