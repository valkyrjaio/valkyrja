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

namespace Valkyrja\Session\Driver;

use Valkyrja\Session\Adapter\Contract\Adapter;
use Valkyrja\Session\Driver\Contract\Driver as Contract;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Driver constructor.
     */
    public function __construct(
        protected Adapter $adapter
    ) {
    }

    /**
     * @inheritDoc
     */
    public function start(): void
    {
        $this->adapter->start();
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->adapter->getId();
    }

    /**
     * @inheritDoc
     */
    public function setId(string $id): void
    {
        $this->adapter->setId($id);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->adapter->getName();
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->adapter->setName($name);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return $this->adapter->isActive();
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->adapter->has($id);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id, mixed $default = null): mixed
    {
        return $this->adapter->get($id, $default);
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, $value): void
    {
        $this->adapter->set($id, $value);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $id): bool
    {
        return $this->adapter->remove($id);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->adapter->all();
    }

    /**
     * @inheritDoc
     */
    public function generateCsrfToken(string $id): string
    {
        return $this->adapter->generateCsrfToken($id);
    }

    /**
     * @inheritDoc
     */
    public function validateCsrfToken(string $id, string $token): void
    {
        $this->adapter->validateCsrfToken($id, $token);
    }

    /**
     * @inheritDoc
     */
    public function isCsrfTokenValid(string $id, string $token): bool
    {
        return $this->adapter->isCsrfTokenValid($id, $token);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->adapter->clear();
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        $this->adapter->destroy();
    }
}
