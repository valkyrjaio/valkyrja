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

namespace Valkyrja\Container\Manager\Trait;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidPublishCallbackException;

use function is_callable;

trait ProvidersAware
{
    /**
     * The publish callbacks for items registered via providers.
     *
     * @var array<class-string, callable(ContainerContract):void>
     */
    protected array $callbacks = [];

    /**
     * The items provided by providers that are published.
     *
     * @var array<class-string, bool>
     */
    protected array $published = [];

    /**
     * @inheritDoc
     */
    public function register(ServiceProviderContract $provider): void
    {
        foreach ($provider->publishers() as $provided => $publishCallback) {
            if (! is_callable($publishCallback)) {
                throw new ContainerInvalidPublishCallbackException("$provided should have a valid callable");
            }

            $this->callbacks[$provided] = $publishCallback;
        }
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    public function isPublished(string $id): bool
    {
        return isset($this->published[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    public function publish(string $id): void
    {
        $publishCallback = $this->getCallback($id);

        if ($publishCallback === null) {
            return;
        }

        $publishCallback($this);

        $this->published[$id] = true;
    }

    /**
     * Get the publish callback for a given id.
     *
     * @param class-string $id The id
     *
     * @return callable(ContainerContract):void|null
     */
    protected function getCallback(string $id): callable|null
    {
        return $this->callbacks[$id]
            ?? null;
    }

    /**
     * Publish an unpublished provided item.
     *
     * @param class-string $id The service id
     */
    protected function publishUnpublishedProvided(string $id): void
    {
        if (isset($this->callbacks[$id]) && ! $this->isPublished($id)) {
            $this->publish($id);
        }
    }
}
