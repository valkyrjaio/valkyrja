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

use Valkyrja\Http\Exceptions\InvalidPath;

/**
 * Trait Path.
 *
 * @author Melech Mizrachi
 *
 * @property string $path
 */
trait Path
{
    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @throws InvalidPath
     *
     * @return string
     */
    protected function validatePath(string $path): string
    {
        if (strpos($path, '?') !== false) {
            throw new InvalidPath('Invalid path provided; must not contain a query string');
        }

        if (strpos($path, '#') !== false) {
            throw new InvalidPath('Invalid path provided; must not contain a URI fragment');
        }

        // TODO: Filter path

        return '/' . ltrim($path, '/');
    }

    /**
     * Retrieve the path component of the URI.
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @return string The URI path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Return an instance with the specified path.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     * If the path is intended to be domain-relative rather than path relative
     * then it must begin with a slash ("/"). Paths not starting with a slash
     * ("/") are assumed to be relative to some base path known to the
     * application or consumer.
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     *
     * @throws InvalidPath for invalid paths.
     *
     * @return static A new instance with the specified path.
     */
    public function withPath(string $path): self
    {
        if ($path === $this->path) {
            return clone $this;
        }

        $path = $this->validatePath($path);

        $new = clone $this;

        $new->path = $path;

        return $new;
    }
}
