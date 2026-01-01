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

/**
 * Interface CookieContract.
 *
 * @author Melech Mizrachi
 */
interface CookieContract extends ValueContract
{
    /**
     * Set the cookie to be deleted.
     *
     * @return static
     */
    public function delete(): static;

    /**
     * Gets the max age of the cookie.
     *
     * @return int
     */
    public function getMaxAge(): int;

    /**
     * Get the cookie's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the cookie's name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the cookie's value.
     *
     * @return string|null
     */
    public function getValue(): string|null;

    /**
     * Set the cookie's value.
     *
     * @param string|null $value The value
     *
     * @return static
     */
    public function withValue(string|null $value = null): static;

    /**
     * Get expire time for the cookie.
     *
     * @return int
     */
    public function getExpire(): int;

    /**
     * Set expire time for the cookie.
     *
     * @param int $expire The expire time
     *
     * @return static
     */
    public function withExpire(int $expire): static;

    /**
     * Get the path the cookie is available to.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set the path the cookie is available to.
     *
     * @param string $path The path
     *
     * @return static
     */
    public function withPath(string $path): static;

    /**
     * Get the domain the cookie is available to.
     *
     * @return string|null
     */
    public function getDomain(): string|null;

    /**
     * Set the domain the cookie is available to.
     *
     * @param string|null $domain The domain
     *
     * @return static
     */
    public function withDomain(string|null $domain = null): static;

    /**
     * Whether the cookie should only be transmitted over a secure HTTPS
     * connection.
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Set whether the cookie should only be transmitted over a secure HTTPS
     * connection.
     *
     * @param bool $secure
     *
     * @return static
     */
    public function withSecure(bool $secure): static;

    /**
     * Whether the cookie will be made accessible only through the HTTP
     * protocol.
     *
     * @return bool
     */
    public function isHttpOnly(): bool;

    /**
     * Set whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @param bool $httpOnly [optional] The flag
     *
     * @return static
     */
    public function withHttpOnly(bool $httpOnly = false): static;

    /**
     * Whether the cookie value should be sent with no url encoding.
     *
     * @return bool
     */
    public function isRaw(): bool;

    /**
     * Set whether the cookie value should be sent with no url encoding.
     *
     * @param bool $raw
     *
     * @return static
     */
    public function withRaw(bool $raw): static;

    /**
     * Get whether the cookie will be available for cross-site requests.
     *
     * @return SameSite|null
     */
    public function getSameSite(): SameSite|null;

    /**
     * Set whether the cookie will be available for cross-site requests.
     *
     * @param SameSite|null $sameSite
     *
     * @return static
     */
    public function withSameSite(SameSite|null $sameSite = null): static;
}
