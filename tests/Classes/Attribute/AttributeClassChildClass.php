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

namespace Valkyrja\Tests\Classes\Attribute;

use Attribute;

/**
 * Attribute child class used for unit testing.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class AttributeClassChildClass extends AttributeClass
{
    public string|null $name = null;

    public string|null $class = null;

    public string|null $constant = null;

    public string|null $property = null;

    public string|null $method = null;

    public bool|null $static = null;

    public bool|null $isOptional = null;

    public mixed $default = null;

    public function __construct(
        int $counter,
        public string $test
    ) {
        parent::__construct($counter);
    }

    /**
     * @param string $class
     *
     * @return void
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * @param string $constant
     *
     * @return void
     */
    public function setConstant(string $constant): void
    {
        $this->constant = $constant;
    }

    /**
     * @param string $property
     *
     * @return void
     */
    public function setProperty(string $property): void
    {
        $this->property = $property;
    }

    /**
     * @param string $method
     *
     * @return void
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @param bool $static
     *
     * @return void
     */
    public function setStatic(bool $static): void
    {
        $this->static = $static;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param bool|null $optional
     */
    public function setIsOptional(?bool $optional): void
    {
        $this->isOptional = $optional;
    }

    /**
     * @param mixed $default
     */
    public function setDefault(mixed $default): void
    {
        $this->default = $default;
    }
}
