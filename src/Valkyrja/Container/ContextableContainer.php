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

namespace Valkyrja\Container;

use Valkyrja\Container\Contract\Container as Contract;
use Valkyrja\Container\Contract\Service;

/**
 * Class ContextableContainer.
 *
 * @author Melech Mizrachi
 */
trait ContextableContainer
{
    /**
     * The context class or function name.
     *
     * @var class-string|string|null
     */
    protected string|null $context = null;

    /**
     * The context id.
     */
    protected string|null $contextId = null;

    /**
     * The context method name.
     */
    protected string|null $contextMember = null;

    /**
     * The context container name.
     */
    protected Contract|null $contextContainer = null;

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withContext(string $context, string|null $member = null): static
    {
        $contextContainer = clone $this;

        $contextContainer->contextContainer = $this;

        $contextContainer->context       = $context;
        $contextContainer->contextMember = $member;
        $contextContainer->contextId     = $this->getContextId($context, $member);

        return $contextContainer;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withoutContext(): static
    {
        $contextContainer = clone $this;

        $contextContainer->contextContainer = null;

        $contextContainer->context       = null;
        $contextContainer->contextMember = null;
        $contextContainer->contextId     = null;

        return $contextContainer;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<Service> $service The service
     */
    public function bind(string $id, string $service): static
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            $this->contextContainer->bind($id, $service);

            return $this;
        }

        parent::bind($id, $service);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bindAlias(string $alias, string $id): static
    {
        if ($this->contextContainer !== null) {
            $id    = $this->getServiceIdInternal($id);
            $alias = $this->getServiceIdInternal($alias);

            $this->contextContainer->bindAlias($alias, $id);

            return $this;
        }

        parent::bindAlias($alias, $id);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string<Service> $singleton The singleton service
     */
    public function bindSingleton(string $id, string $singleton): static
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            $this->contextContainer->bindSingleton($id, $singleton);

            return $this;
        }

        parent::bindSingleton($id, $singleton);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCallable(string $id, callable $callable): static
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            $this->contextContainer->setCallable($id, $callable);

            return $this;
        }

        parent::setCallable($id, $callable);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSingleton(string $id, mixed $singleton): static
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            $this->contextContainer->setSingleton($id, $singleton);

            return $this;
        }

        parent::setSingleton($id, $singleton);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isAlias(string $id): bool
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->isAlias($id);
        }

        return parent::isAlias($id);
    }

    /**
     * @inheritDoc
     */
    public function isCallable(string $id): bool
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->isCallable($id);
        }

        return parent::isCallable($id);
    }

    /**
     * @inheritDoc
     */
    public function isService(string $id): bool
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->isService($id);
        }

        return parent::isService($id);
    }

    /**
     * @inheritDoc
     */
    public function isSingleton(string $id): bool
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->isSingleton($id);
        }

        return parent::isSingleton($id);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id, array $arguments = []): mixed
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->get($id, $arguments);
        }

        return parent::get($id, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getCallable(string $id, array $arguments = []): mixed
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->getCallable($id, $arguments);
        }

        return parent::getCallable($id, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getService(string $id, array $arguments = []): Service
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->getService($id, $arguments);
        }

        return parent::getService($id, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getSingleton(string $id): mixed
    {
        if ($this->contextContainer !== null) {
            $id = $this->getServiceIdInternal($id);

            return $this->contextContainer->getSingleton($id);
        }

        return parent::getSingleton($id);
    }

    /**
     * Get a context id with optional context.
     *
     * @param string      $context The context class or function name
     * @param string|null $member  [optional] The context member name
     *
     * @return string
     */
    protected function getContextId(string $context, string|null $member = null): string
    {
        return $context . ($member ?? '');
    }

    /**
     * Get a service id and ensure that it is published if it is provided.
     *
     * @param class-string|string $id The service id
     *
     * @return string
     */
    protected function getServiceIdAndEnsurePublished(string $id): string
    {
        // Get an aliased service id if it exists
        $id = $this->getServiceIdInternal($id);

        $this->publishUnpublishedProvided($id);

        return $id;
    }

    /**
     * Get the context service id.
     *
     * @param class-string|string $id The service id
     *
     * @return string
     */
    protected function getServiceIdInternal(string $id): string
    {
        $id = $this->getAliasedServiceId($id);

        if ($this->context === null) {
            return $id;
        }

        // serviceId@context
        // serviceId@context::method
        return $id . ($this->contextId ?? '');
    }
}
