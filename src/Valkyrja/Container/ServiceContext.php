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

namespace Valkyrja\Container;

use Closure;

/**
 * Interface ServiceContext.
 *
 * @author Melech Mizrachi
 */
interface ServiceContext extends Service
{
    /**
     * Get the context class.
     *
     * @return string
     */
    public function getContextClass(): ?string;

    /**
     * Set the context class.
     *
     * @param string $contextClass The context class
     *
     * @return $this
     */
    public function setContextClass(string $contextClass): self;

    /**
     * Get the context property.
     *
     * @return string|null
     */
    public function getContextProperty(): ?string;

    /**
     * Set the context property.
     *
     * @param string $contextProperty The context property
     *
     * @return $this
     */
    public function setContextProperty(string $contextProperty): self;

    /**
     * Get the context method.
     *
     * @return string|null
     */
    public function getContextMethod(): ?string;

    /**
     * Set the context method.
     *
     * @param string $contextMethod The context method
     *
     * @return $this
     */
    public function setContextMethod(string $contextMethod): self;

    /**
     * Get the context function.
     *
     * @return string|null
     */
    public function getContextFunction(): ?string;

    /**
     * Set the context function.
     *
     * @param string $contextFunction The context function
     *
     * @return $this
     */
    public function setContextFunction(string $contextFunction): self;

    /**
     * Get the context closure.
     *
     * @return Closure|null
     */
    public function getContextClosure(): ?Closure;

    /**
     * Set the context closure.
     *
     * @param Closure $contextClosure The context closure.
     *
     * @return $this
     */
    public function setContextClosure(Closure $contextClosure): self;
}
