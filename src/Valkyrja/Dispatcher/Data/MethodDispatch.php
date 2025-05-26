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

namespace Valkyrja\Dispatcher\Data;

use Valkyrja\Dispatcher\Data\Contract\MethodDispatch as Contract;
use Valkyrja\Dispatcher\Exception\InvalidArgumentException;

use function is_array;
use function is_string;

/**
 * Class MethodDispatch.
 *
 * @author Melech Mizrachi
 */
class MethodDispatch extends ClassDispatch implements Contract
{
    /**
     * @param class-string                 $class        The class name
     * @param array<array-key, mixed>|null $arguments    The arguments
     * @param string[]|null                $dependencies The dependencies
     */
    public function __construct(
        string $class,
        protected string $method,
        protected bool $isStatic = false,
        array|null $arguments = null,
        array|null $dependencies = null
    ) {
        parent::__construct(
            class: $class,
            arguments: $arguments,
            dependencies: $dependencies
        );
    }

    /**
     * @inheritDoc
     */
    public static function fromCallableOrArray(callable|array $callable): static
    {
        if (! is_array($callable)) {
            throw new InvalidArgumentException('Callable must be an array.');
        }

        /** @var class-string|object $className */
        $className = $callable[0]
            ?? throw new InvalidArgumentException('Callable must be an array with a valid class name');

        if (! is_string($className)) {
            throw new InvalidArgumentException('First part of the callable array must be a class-string');
        }

        $method = $callable[1]
            ?? throw new InvalidArgumentException('Callable must be an array with a valid method name');

        return new static(
            class: $className,
            method: $method,
            isStatic: true
        );
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): static
    {
        $new = clone $this;

        $new->method = $method;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @inheritDoc
     */
    public function withIsStatic(bool $isStatic): static
    {
        $new = clone $this;

        $new->isStatic = $isStatic;

        return $new;
    }
}
