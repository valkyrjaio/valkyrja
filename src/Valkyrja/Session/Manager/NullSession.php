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

namespace Valkyrja\Session\Manager;

use Override;
use Random\RandomException;
use Valkyrja\Session\Manager\Contract\SessionContract as Contract;
use Valkyrja\Session\Throwable\Exception\InvalidCsrfToken;
use Valkyrja\Session\Throwable\Exception\InvalidSessionId;
use Valkyrja\Session\Throwable\Exception\SessionStartFailure;

use function bin2hex;
use function hash_equals;
use function is_string;
use function preg_match;
use function random_bytes;

/**
 * Class NullSession.
 */
class NullSession implements Contract
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
     * NullSession constructor.
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
            $this->id = $sessionId;
        }

        // If a session name is provided
        if (is_string($sessionName)) {
            // Set the name
            $this->name = $sessionName;
        }

        // Start the session
        $this->start();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function start(): void
    {
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

        /** @var mixed $sessionToken */
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
     * Validate an id.
     *
     * @param string $id The id
     *
     * @return void
     */
    protected function validateId(string $id): void
    {
        if (! preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $id)) {
            throw new InvalidSessionId(
                "The session id, '$id', is invalid! "
                . 'Session id can only contain alpha numeric characters, dashes, commas, '
                . 'and be at least 1 character in length but up to 128 characters long.'
            );
        }
    }
}
