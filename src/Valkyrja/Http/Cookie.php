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

namespace Valkyrja\Http;

use Valkyrja\Http\Exceptions\InvalidSameSiteTypeException;
use Valkyrja\Support\Model\Model;

/**
 * Interface Cookie.
 *
 * @author Melech Mizrachi
 */
interface Cookie extends Model
{
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
     * @return Cookie
     */
    public function setName(string $name): self;

    /**
     * Get the cookie's value.
     *
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * Set the cookie's value.
     *
     * @param string|null $value The value
     *
     * @return Cookie
     */
    public function setValue(string $value = null): self;

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
     * @return Cookie
     */
    public function setExpire(int $expire): self;

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
     * @return Cookie
     */
    public function setPath(string $path): self;

    /**
     * Get the domain the cookie is available to.
     *
     * @return string|null
     */
    public function getDomain(): ?string;

    /**
     * Set the domain the cookie is available to.
     *
     * @param string|null $domain The domain
     *
     * @return Cookie
     */
    public function setDomain(string $domain = null): self;

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
     * @return Cookie
     */
    public function setSecure(bool $secure): self;

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
     * @return Cookie
     */
    public function setHttpOnly(bool $httpOnly = false): self;

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
     * @return Cookie
     */
    public function setRaw(bool $raw): self;

    /**
     * Get whether the cookie will be available for cross-site requests.
     *
     * @return string|null
     */
    public function getSameSite(): ?string;

    /**
     * Set whether the cookie will be available for cross-site requests.
     *
     * @param string|null $sameSite
     *
     * @throws InvalidSameSiteTypeException
     *
     * @return Cookie
     */
    public function setSameSite(string $sameSite = null): self;
}
