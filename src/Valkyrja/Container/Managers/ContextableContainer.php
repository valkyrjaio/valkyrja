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

namespace Valkyrja\Container\Managers;

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
     *
     * @var string|null
     */
    protected string|null $contextId = null;

    /**
     * The context method name.
     *
     * @var string|null
     */
    protected string|null $contextMember = null;

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withContext(string $context, string $member = null): static
    {
        $contextContainer = new static($this->config, $this->debug);

        $contextContainer->context       = $context;
        $contextContainer->contextMember = $member;
        $contextContainer->contextId     = $this->getServiceId('', $context, $member);

        return $contextContainer;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function withoutContext(): static
    {
        $contextContainer = new static($this->config, $this->debug);

        $contextContainer->context       = null;
        $contextContainer->contextMember = null;
        $contextContainer->contextId     = null;

        return $contextContainer;
    }

    /**
     * Get a service id with optional context.
     *
     * @param class-string|string $id The service id
     * @param string|null         $context   [optional] The context class or function name
     * @param string|null         $member    [optional] The context member name
     *
     * @return string
     */
    protected function getServiceId(string $id, string $context = null, string $member = null): string
    {
        if ($context === null) {
            return $id;
        }

        return $id . $context . ($member ?? '');
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
