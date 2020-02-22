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

/**
 * Trait Fragment.
 *
 * @author Melech Mizrachi
 *
 * @property string $fragment
 */
trait Fragment
{
    /**
     * Validate a fragment.
     *
     * @param string $fragment The fragment
     *
     * @return string
     */
    protected function validateFragment(string $fragment): string
    {
        // TODO: Filter fragment

        return $fragment;
    }

    /**
     * Retrieve the fragment component of the URI.
     * If no fragment is present, this method MUST return an empty string.
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     *
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Return an instance with the specified URI fragment.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     *
     * @return static A new instance with the specified fragment.
     */
    public function withFragment(string $fragment): self
    {
        if ($fragment === $this->fragment) {
            return clone $this;
        }

        $fragment = $this->validateFragment($fragment);

        $new = clone $this;

        $new->fragment = $fragment;

        return $new;
    }
}
