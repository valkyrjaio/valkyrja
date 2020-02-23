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

use Valkyrja\Http\Exceptions\InvalidQuery;

/**
 * Trait Query.
 *
 * @author Melech Mizrachi
 *
 * @property string $query
 */
trait Query
{
    /**
     * Validate a query.
     *
     * @param string $query The query
     *
     * @throws InvalidQuery
     *
     * @return string
     */
    protected function validateQuery(string $query): string
    {
        if (strpos($query, '#') !== false) {
            throw new InvalidQuery('Query string must not include a URI fragment');
        }

        // TODO: Filter query

        return $query;
    }

    /**
     * Retrieve the query string of the URI.
     * If no query string is present, this method MUST return an empty string.
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @return string The URI query string.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Return an instance with the specified query string.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @throws InvalidQuery for invalid query strings.
     *
     * @return static A new instance with the specified query string.
     */
    public function withQuery(string $query): self
    {
        if ($query === $this->query) {
            return clone $this;
        }

        $query = $this->validateQuery($query);

        $new = clone $this;

        $new->query = $query;

        return $new;
    }
}
