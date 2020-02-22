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

namespace Valkyrja\HttpMessage\Uris;

use Valkyrja\HttpMessage\Enums\Port as PortEnum;
use Valkyrja\HttpMessage\Enums\Scheme as SchemeEnum;
use Valkyrja\HttpMessage\Exceptions\InvalidPort;

/**
 * Trait Port.
 *
 * @author Melech Mizrachi
 *
 * @property string   $host
 * @property int|null $port
 * @property string   $scheme
 */
trait Port
{
    /**
     * Validate a port.
     *
     * @param int $port The port
     *
     * @throws InvalidPort
     *
     * @return void
     */
    protected function validatePort(int $port = null): void
    {
        if ($port !== null && ($port < 1 || $port > 65535)) {
            throw new InvalidPort(
                sprintf('Invalid port "%d" specified; must be a valid TCP/UDP port', $port)
            );
        }
    }

    /**
     * Retrieve the port component of the URI.
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard
     * port used with the current scheme, this method SHOULD return null.
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return int|null The URI port.
     */
    public function getPort(): ?int
    {
        return $this->isStandardPort() ? null : $this->port;
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
    public function getHostPort(): string
    {
        $host = $this->getHost();

        if ($host && $port = $this->getPort()) {
            $host .= ':' . $port;
        }

        return $host;
    }

    /**
     * Return an instance with the specified port.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *                       removes the port information.
     *
     * @throws InvalidPort for invalid ports.
     *
     * @return static A new instance with the specified port.
     */
    public function withPort(int $port = null): self
    {
        if ($port === $this->port) {
            return clone $this;
        }

        $this->validatePort($port);

        $new = clone $this;

        $new->port = $port;

        return $new;
    }

    /**
     * Retrieve the host component of the URI.
     * If no host is present, this method MUST return an empty string.
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     *
     * @return string The URI host.
     */
    abstract public function getHost(): string;

    /**
     * Determine whether this uri is on a standard port for the scheme.
     *
     * @return bool
     */
    protected function isStandardPort(): bool
    {
        if (! $this->scheme) {
            return $this->host && $this->port === null;
        }

        if (! $this->host || $this->port === null) {
            return true;
        }

        return $this->isStandardHttpPort() || $this->isStandardHttpsPort();
    }

    /**
     * Is standard HTTP port.
     *
     * @return bool
     */
    protected function isStandardHttpPort(): bool
    {
        return SchemeEnum::HTTP === $this->scheme && $this->port === PortEnum::HTTP;
    }

    /**
     * Is standard HTTPS port.
     *
     * @return bool
     */
    protected function isStandardHttpsPort(): bool
    {
        return SchemeEnum::HTTPS === $this->scheme && $this->port === PortEnum::HTTPS;
    }
}
