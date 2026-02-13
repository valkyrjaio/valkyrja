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

namespace Valkyrja\Session\Manager\Abstract;

use Override;
use Random\RandomException;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Throwable\Exception\InvalidCsrfToken;
use Valkyrja\Session\Throwable\Exception\InvalidSessionId;
use Valkyrja\Session\Throwable\Exception\SessionStartFailure;

use function bin2hex;
use function hash_equals;
use function is_string;
use function random_bytes;

abstract class Session implements SessionContract
{
    /**
     * The session id.
     *
     * @var string
     */
    protected string $id = '';

    /**
     * The session name.
     *
     * @var string
     */
    protected string $name = '';

    /**
     * The session data.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     *
     * @throws InvalidSessionId
     * @throws SessionStartFailure
     */
    public function __construct(
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        // If a session id is provided
        if (is_string($sessionId)) {
            $this->validateId($sessionId);

            // Set the id
            $this->setId($sessionId);
        }

        // If a session name is provided
        if (is_string($sessionName)) {
            // Set the name
            $this->setName($sessionName);
        }

        // Start the session
        $this->start();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setId(string $id): void
    {
        $this->validateId($id);

        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isActive(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $id): bool
    {
        return isset($this->data[$id]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $id, mixed $default = null): mixed
    {
        return $this->data[$id] ?? $default;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function set(string $id, $value): void
    {
        $this->data[$id] = $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function remove(string $id): bool
    {
        if (! $this->has($id)) {
            return false;
        }

        unset($this->data[$id]);

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     *
     * @throws RandomException
     */
    #[Override]
    public function generateCsrfToken(string $id): string
    {
        $token = bin2hex(random_bytes(64));

        $this->set($id, $token);

        return $token;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function validateCsrfToken(string $id, string $token): void
    {
        if (! $this->isCsrfTokenValid($id, $token)) {
            throw new InvalidCsrfToken("CSRF token id: `$id` has invalid token of `$token` provided");
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isCsrfTokenValid(string $id, string $token): bool
    {
        if (! $this->has($id)) {
            return false;
        }

        /** @var scalar|object|array<array-key, mixed>|resource|null $sessionToken */
        $sessionToken = $this->get($id);

        if (is_string($sessionToken) && hash_equals($token, $sessionToken)) {
            $this->remove($id);

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function destroy(): void
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function start(): void;

    /**
     * Validate an id.
     *
     * @param non-empty-string $id The id
     */
    protected function validateId(string $id): void
    {
    }
}
