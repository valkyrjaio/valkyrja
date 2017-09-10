<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

use Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion;

/**
 * Trait MessageTrait.
 *
 * @author Melech Mizrachi
 */
trait MessageTrait
{
    /**
     * The headers with normalized header names.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Original header names.
     *
     * @var array
     */
    protected $headerNames = [];

    /**
     * The protocol.
     *
     * @var string
     */
    protected $protocol = '1.1';

    /**
     * The stream.
     *
     * @var \Valkyrja\HttpMessage\Stream
     */
    protected $stream;

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1",
     * "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion
     *
     * @return static
     */
    public function withProtocolVersion(string $version)
    {
        $this->validateProtocolVersion($version);

        $this->protocol = $version;

        return $this;
    }

    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve
     * the exact case in which headers were originally specified.
     *
     * @return string[][] Returns an associative array of the message's
     *                    headers. Each key MUST be a header name, and each
     *                    value MUST be an array of strings for that header.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return bool Returns true if any header names match the given header
     *              name using a case-insensitive string comparison. Returns
     *              false if no matching header name is found in the message.
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headerNames[strtolower($name)]);
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string[] An array of string values as provided for the given
     *                  header. If the header does not appear in the message,
     *                  this method MUST return an empty array.
     */
    public function getHeader(string $name): array
    {
        if (! $this->hasHeader($name)) {
            return [];
        }

        $name = $this->headerNames[strtolower($name)];

        return $this->headers[$name];
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string A string of values as provided for the given header
     *                concatenated together using a comma. If the header does
     *                not appear in the message, this method MUST return an
     *                empty string.
     */
    public function getHeaderLine(string $name): string
    {
        $value = $this->getHeader($name);

        if (! $value) {
            return '';
        }

        return implode(',', $value);
    }

    /**
     * Return an instance with the provided value replacing the specified
     * header.
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * @param string   $name      Case-insensitive header field name.
     * @param string[] ...$values Header values.
     *
     * @throws \InvalidArgumentException for invalid header names or values.
     *
     * @return static
     */
    public function withHeader(string $name, string ...$values)
    {
        $normalized = strtolower($name);

        $new = clone $this;

        if ($new->hasHeader($name)) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }

        $values = $this->filterHeaderValues(...$values);

        $new->headerNames[$normalized] = $name;
        $new->headers[$name]           = $values;

        return $new;
    }

    /**
     * Return an instance with the specified header appended with the given
     * value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * @param string   $name      Case-insensitive header field name to add.
     * @param string[] ...$values Header values.
     *
     * @throws \InvalidArgumentException for invalid header names or values.
     *
     * @return static
     */
    public function withAddedHeader(string $name, string ...$values)
    {
        if (! $this->hasHeader($name)) {
            return $this->withHeader($name);
        }

        $name = $this->headerNames[strtolower($name)];

        $new = clone $this;

        $values = $this->filterHeaderValues(...$values);

        $new->headers[$name] = array_merge($this->headers[$name], $values);

        return $new;
    }

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     *
     * @return static
     */
    public function withoutHeader(string $name)
    {
        if (! $this->hasHeader($name)) {
            return clone $this;
        }

        $normalized = strtolower($name);
        $original   = $this->headerNames[$normalized];
        $new        = clone $this;

        unset($new->headers[$original], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * Gets the body of the message.
     *
     * @return \Valkyrja\HttpMessage\Stream Returns the body as a stream.
     */
    public function getBody(): Stream
    {
        return $this->stream;
    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param Stream $body Body.
     *
     * @throws \InvalidArgumentException When the body is not valid.
     *
     * @return static
     */
    public function withBody(Stream $body)
    {
        $new = clone $this;

        $new->stream = $body;

        return $new;
    }

    /**
     * Validate the protocol version.
     *
     * @param string $version
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion
     *
     * @return void
     */
    protected function validateProtocolVersion(string $version): void
    {
        // HTTP/1 uses a "<major>.<minor>" numbering scheme to indicate
        // versions of the protocol, while HTTP/2 does not.
        if (! preg_match('#^(1\.[01]|2)$#', $version)) {
            throw new InvalidProtocolVersion(
                sprintf(
                    'Unsupported HTTP protocol version "%s" provided',
                    $version
                )
            );
        }
    }

    /**
     * Filter header values.
     *
     * @param string[] ...$values Header values
     *
     * @return string[]
     */
    protected function filterHeaderValues(string ...$values): array
    {
        return $values;
    }

    /**
     * Inject a header in a headers array.
     *
     * @param string $header   The header to set
     * @param string $value    The value to set
     * @param array  $headers  [optional] The headers
     * @param bool   $override [optional] Whether to override any existing value
     *
     * @return array
     */
    protected function injectHeader(
        string $header,
        string $value,
        array $headers = null,
        bool $override = false
    ): array {
        // The headers
        $headers = $headers ?? [];
        // Normalize the content type header
        $normalized = strtolower($header);
        // The original value for the header (if it exists in the headers array)
        // Defauls to the value passed in
        $originalValue = $value;

        // Iterate through all the headers
        foreach ($headers as $headerIndex => $headerValue) {
            // Normalize the header name and check if it matches the normalized
            // passed in header
            if (strtolower($headerIndex) === $normalized) {
                // Set the original value as this header value
                $originalValue = $headerValue;

                // Unset the header as we want to use the header string that was
                // passed in as the header
                unset($headers[$headerIndex]);
            }
        }

        // Set the header in the headers list
        $headers[$header] = [$override ? $originalValue : $value];

        return $headers;
    }
}
