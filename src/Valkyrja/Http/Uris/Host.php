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

use InvalidArgumentException;

/**
 * Trait Host.
 *
 * @author Melech Mizrachi
 *
 * @property string $host
 */
trait Host
{
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
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Return an instance with the specified host.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     *
     * @throws InvalidArgumentException for invalid hostnames.
     *
     * @return static A new instance with the specified host.
     */
    public function withHost(string $host): self
    {
        if ($host === $this->host) {
            return clone $this;
        }

        $new = clone $this;

        $new->host = $host;

        return $new;
    }
}
