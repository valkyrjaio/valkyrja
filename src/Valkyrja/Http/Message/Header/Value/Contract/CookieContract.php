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

namespace Valkyrja\Http\Message\Header\Value\Contract;

use Valkyrja\Http\Message\Enum\SameSite;

interface CookieContract extends ValueContract
{
    /**
     * Set the cookie to be deleted.
     */
    public function delete(): static;

    /**
     * Gets the max age of the cookie.
     */
    public function getMaxAge(): int;

    /**
     * Get the cookie's name.
     */
    public function getName(): string;

    /**
     * Set the cookie's name.
     *
     * @param string $name The name
     */
    public function withName(string $name): static;

    /**
     * Get the cookie's value.
     */
    public function getValue(): string|null;

    /**
     * Set the cookie's value.
     *
     * @param string|null $value The value
     */
    public function withValue(string|null $value = null): static;

    /**
     * Get expire time for the cookie.
     */
    public function getExpire(): int;

    /**
     * Set expire time for the cookie.
     *
     * @param int $expire The expire time
     */
    public function withExpire(int $expire): static;

    /**
     * Get the path the cookie is available to.
     */
    public function getPath(): string;

    /**
     * Set the path the cookie is available to.
     *
     * @param string $path The path
     */
    public function withPath(string $path): static;

    /**
     * Get the domain the cookie is available to.
     */
    public function getDomain(): string|null;

    /**
     * Set the domain the cookie is available to.
     *
     * @param string|null $domain The domain
     */
    public function withDomain(string|null $domain = null): static;

    /**
     * Whether the cookie should only be transmitted over a secure HTTPS
     * connection.
     */
    public function isSecure(): bool;

    /**
     * Set whether the cookie should only be transmitted over a secure HTTPS
     * connection.
     *
     *
     */
    public function withSecure(bool $secure): static;

    /**
     * Whether the cookie will be made accessible only through the HTTP
     * protocol.
     */
    public function isHttpOnly(): bool;

    /**
     * Set whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @param bool $httpOnly [optional] The flag
     */
    public function withHttpOnly(bool $httpOnly = false): static;

    /**
     * Whether the cookie value should be sent with no url encoding.
     */
    public function isRaw(): bool;

    /**
     * Set whether the cookie value should be sent with no url encoding.
     *
     *
     */
    public function withRaw(bool $raw): static;

    /**
     * Get whether the cookie will be available for cross-site requests.
     */
    public function getSameSite(): SameSite|null;

    /**
     * Set whether the cookie will be available for cross-site requests.
     *
     *
     */
    public function withSameSite(SameSite|null $sameSite = null): static;
}
