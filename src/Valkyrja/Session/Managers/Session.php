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

namespace Valkyrja\Session\Managers;

use Valkyrja\Manager\Managers\Manager;
use Valkyrja\Session\Config\Config;
use Valkyrja\Session\Driver;
use Valkyrja\Session\Factory;
use Valkyrja\Session\Session as Contract;

/**
 * Class Sessions.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Driver, Factory>
 */
class Session extends Manager implements Contract
{
    /**
     * Session constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['sessions'];
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        /** @var Driver $driver */
        $driver = parent::use($name);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function start(): void
    {
        $this->use()->start();
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->use()->getId();
    }

    /**
     * @inheritDoc
     */
    public function setId(string $id): void
    {
        $this->use()->setId($id);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->use()->getName();
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->use()->setName($name);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return $this->use()->isActive();
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->use()->has($id);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id, mixed $default = null): mixed
    {
        return $this->use()->get($id, $default);
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, $value): void
    {
        $this->use()->set($id, $value);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $id): bool
    {
        return $this->use()->remove($id);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->use()->all();
    }

    /**
     * @inheritDoc
     */
    public function generateCsrfToken(string $id): string
    {
        return $this->use()->generateCsrfToken($id);
    }

    /**
     * @inheritDoc
     */
    public function validateCsrfToken(string $id, string $token): void
    {
        $this->use()->validateCsrfToken($id, $token);
    }

    /**
     * @inheritDoc
     */
    public function isCsrfTokenValid(string $id, string $token): bool
    {
        return $this->use()->isCsrfTokenValid($id, $token);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->use()->clear();
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        $this->use()->destroy();
    }
}
