<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Uris;

use Valkyrja\Http\Enums\Scheme as SchemeEnum;
use Valkyrja\Http\Exceptions\InvalidScheme;

/**
 * Trait Scheme.
 *
 * @author Melech Mizrachi
 *
 * @property string $scheme
 */
trait Scheme
{
    /**
     * Determine whether the URI is secure.
     * If a scheme is present, and the value matches 'https',
     * this method MUST return true.
     * If the scheme is present, and the value does not match
     * 'https', this method MUST return false.
     * If no scheme is present, this method MUST return false.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->getScheme() === 'https';
    }

    /**
     * Retrieve the scheme component of the URI.
     * If no scheme is present, this method MUST return an empty string.
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Retrieve the scheme, host, and port components of the URI.
     * If a scheme is present, a host is present, and a port is present,
     * and the port is non-standard for the current scheme, this method
     * MUST return the scheme, followed by the host, followed by the
     * port in a fashion akin to the following <scheme://host:port>.
     * If a scheme is present, a host is present, and a port is present,
     * and the port is standard for the current scheme, this method
     * MUST return only the scheme followed by the host, in a
     * fashion akin to the following <scheme://host>.
     * If a scheme is present, a host is present, and a port is not
     * present, this method MUST return only the scheme followed by
     * the host, in a fashion akin to the following <scheme://host>.
     * If a scheme is not present, a host is present, and a port is
     * present, this method MUST return only the host, followed by
     * the port, in a fashion akin to the following <host:port>.
     * If a scheme is not present, a host is present, and a port is
     * not present, this method MUST return only the host.
     * If a scheme is not present, a host is not present, and a port
     * is not present, this method MUST return an empty string.
     * If a scheme is present, a host is not present, and a port
     * is either present or not, this method MUST return an
     * empty string.
     *
     * @return string
     */
    public function getSchemeHostPort(): string
    {
        $hostPort = $this->getHostPort();
        $scheme   = $this->getScheme();

        return $hostPort && $scheme ? $scheme . '://' . $hostPort : $hostPort;
    }

    /**
     * Retrieve the host and port component of the URI.
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it, along with the host component, as
     * a string in a fashion akin to the following <host:port>.
     * If the port is the standard port used with the current scheme, this
     * method SHOULD return only the host component.
     * If no port is present, and no scheme is present, this method MUST return
     * only the host component.
     * If no port is present, no host is present, and no scheme is present,
     * this method MUST return an empty string.
     * If no port is resent, no host is present, and a scheme is present,
     * this method MUST return an empty string.
     * if no port is present, a host is present, and a scheme is present,
     * this method MUST return only the host component.
     *
     * @return string
     */
    abstract public function getHostPort(): string;

    /**
     * Return an instance with the specified scheme.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @throws InvalidScheme for invalid or
     *          unsupported schemes.
     *
     * @return static A new instance with the specified scheme.
     */
    public function withScheme(string $scheme): self
    {
        if ($scheme === $this->scheme) {
            return clone $this;
        }

        $scheme = $this->validateScheme($scheme);

        $new = clone $this;

        $new->scheme = $scheme;

        return $new;
    }

    /**
     * Validate a scheme.
     *
     * @param string $scheme The scheme
     *
     * @throws InvalidScheme
     *
     * @return string
     */
    protected function validateScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);
        $scheme = (string) preg_replace('#:(//)?$#', '', $scheme);

        if (! $scheme) {
            return '';
        }

        if (SchemeEnum::HTTP !== $scheme && $scheme !== SchemeEnum::HTTPS) {
            throw new InvalidScheme(
                sprintf('Invalid scheme "%s" specified; must be either "http" or "https"', $scheme)
            );
        }

        return $scheme;
    }
}
