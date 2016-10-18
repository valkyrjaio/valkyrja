<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Response.php
 */

namespace Valkyrja\Http;

use \Valkyrja\Contracts\Http\Response as ResponseContract;

/**
 * Class Response
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class Response implements ResponseContract
{
    /**
     * Response headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Cache control.
     *
     * @var array
     */
    protected $cacheControl = [];

    /**
     * Response cookies.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * Response content.
     *
     * @var string
     */
    protected $content;

    /**
     * Response protocol version.
     *
     * @var string
     */
    protected $version;

    /**
     * Response status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Response status text.
     *
     * @var string
     */
    protected $statusText;

    /**
     * Response charset.
     *
     * @var string
     */
    protected $charset;

    /**
     * @inheritdoc
     */
    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * @inheritdoc
     */
    public static function create($content = '', $status = 200, $headers = [])
    {
        return new static($content, $status, $headers);
    }

    /**
     * @inheritdoc
     */
    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content)
            && !is_callable(
                [
                    $content,
                    '__toString',
                ]
            )
        ) {
            throw new \UnexpectedValueException(
                sprintf(
                    'The Response content must be a string or object implementing __toString(), "%s" given.',
                    gettype($content)
                )
            );
        }

        $this->content = (string) $content;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function setProtocolVersion($version = '1.0')
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = $code = (int) $code;

        if ($this->isInvalid()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }

        if (null === $text) {
            $statusTexts = static::STATUS_TEXTS;

            $this->statusText = isset($statusTexts[$code])
                ? $statusTexts[$code]
                : 'unknown status';

            return $this;
        }

        if (false === $text) {
            $this->statusText = '';

            return $this;
        }

        $this->statusText = $text;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @inheritdoc
     */
    public function setHeaders(array $headers = [])
    {
        $this->headers = $headers;

        if (!$this->hasHeader('Cache-Control')) {
            $this->setHeader('Cache-Control', '');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getHeader($header)
    {
        return $this->hasHeader($header)
            ? $this->headers[$header]
            : false;
    }

    /**
     * @inheritdoc
     */
    public function hasHeader($header)
    {
        return isset($this->headers[$header]);
    }

    /**
     * @inheritdoc
     */
    public function removeHeader($header)
    {
        if (!$this->hasHeader($header)) {
            return $this;
        }

        unset($this->headers[$header]);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDateHeader()
    {
        if (!$this->hasHeader('Date')) {
            $this->setDateHeader(\DateTime::createFromFormat('U', time()));
        }

        return $this->getHeader('Date');
    }

    /**
     * @inheritdoc
     */
    public function setDateHeader(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->setHeader('Date', $date->format('D, d M Y H:i:s') . ' GMT');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCookies($asString = true)
    {
        if (!$asString) {
            return $this->cookies;
        }

        $flattenedCookies = [];

        foreach ($this->cookies as $path) {
            foreach ($path as $cookies) {
                foreach ($cookies as $cookie) {
                    $flattenedCookies[] = $cookie;
                }
            }
        }

        return $flattenedCookies;
    }

    /**
     * @inheritdoc
     */
    public function setCookie(
        $name,
        $value = null,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true,
        $raw = false,
        $sameSite = null
    ) {
        $this->cookies[$domain][$path][$name] = [
            'name'     => (string) $name,
            'value'    => (string) $value,
            'expire'   => (string) $expire,
            'path'     => (empty($path)
                ? '/'
                : (string) $path),
            'domain'   => (string) $domain,
            'secure'   => (bool) $secure,
            'httpOnly' => (bool) $httpOnly,
            'raw'      => (bool) $raw,
            'sameSite' => (in_array(
                $sameSite,
                [
                    'lax',
                    'strict',
                    null,
                ]
            )
                ? $sameSite
                : null),
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeCookie($name, $path = '/', $domain = null)
    {
        if (null === $path) {
            $path = '/';
        }

        unset($this->cookies[$domain][$path][$name]);

        if (empty($this->cookies[$domain][$path])) {
            unset($this->cookies[$domain][$path]);

            if (empty($this->cookies[$domain])) {
                unset($this->cookies[$domain]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function addCacheControl($name, $value = null)
    {
        $this->cacheControl[$name] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCacheControl($name)
    {
        return $this->hasCacheControl($name)
            ? $this->cacheControl[$name]
            : false;
    }

    /**
     * @inheritdoc
     */
    public function hasCacheControl($name)
    {
        return isset($this->cacheControl[$name]);
    }

    /**
     * @inheritdoc
     */
    public function removeCacheControl($name)
    {
        if (!$this->hasCacheControl($name)) {
            return $this;
        }

        unset($this->cacheControl[$name]);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isCacheable()
    {
        if (!in_array(
            $this->statusCode,
            [
                200,
                203,
                300,
                301,
                302,
                404,
                410,
            ]
        )
        ) {
            return false;
        }

        if ($this->hasCacheControl('no-store') || $this->hasCacheControl('private')) {
            return false;
        }

        return $this->isValidateable() || $this->isFresh();
    }

    /**
     * @inheritdoc
     */
    public function isFresh()
    {
        return $this->getTtl() > 0;
    }

    /**
     * @inheritdoc
     */
    public function isValidateable()
    {
        return $this->hasHeader('Last-Modified') || $this->hasHeader('ETag');
    }

    /**
     * @inheritdoc
     */
    public function setPrivate()
    {
        $this->removeCacheControl('public');
        $this->addCacheControl('private');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPublic()
    {
        $this->addCacheControl('public');
        $this->removeCacheControl('private');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAge()
    {
        if (null !== $age = $this->getHeader('Age')) {
            return (int) $age;
        }

        return max(
            time() - date('U', strtotime($this->getDateHeader())),
            0
        );
    }

    /**
     * @inheritdoc
     */
    public function expire()
    {
        if ($this->isFresh()) {
            $this->setHeader('Age', $this->getMaxAge());
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExpires()
    {
        try {
            return $this->getHeader('Expires');
        }
        catch (\RuntimeException $e) {
            // according to RFC 2616 invalid date formats (e.g. "0" and "-1") must be treated as in the past
            return \DateTime::createFromFormat(DATE_RFC2822, 'Sat, 01 Jan 00 00:00:00 +0000');
        }
    }

    /**
     * @inheritdoc
     */
    public function setExpires(\DateTime $date = null)
    {
        if (null === $date) {
            $this->removeHeader('Expires');
        }
        else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->setHeader('Expires', $date->format('D, d M Y H:i:s') . ' GMT');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMaxAge()
    {
        if ($this->hasCacheControl('s-maxage')) {
            return (int) $this->getCacheControl('s-maxage');
        }

        if ($this->hasCacheControl('max-age')) {
            return (int) $this->getCacheControl('max-age');
        }

        if (null !== $this->getExpires()) {
            return date('U', strtotime($this->getExpires())) - date('U', strtotime($this->getDateHeader()));
        }

        return 0;
    }

    /**
     * @inheritdoc
     */
    public function setMaxAge($value)
    {
        $this->addCacheControl('max-age', $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSharedMaxAge($value)
    {
        $this->setPublic();
        $this->addCacheControl('s-maxage', $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTtl()
    {
        if (null !== $maxAge = $this->getMaxAge()) {
            return $maxAge - $this->getAge();
        }

        return 0;
    }

    /**
     * @inheritdoc
     */
    public function setTtl($seconds)
    {
        $this->setSharedMaxAge($this->getAge() + $seconds);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setClientTtl($seconds)
    {
        $this->setMaxAge($this->getAge() + $seconds);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLastModified()
    {
        return $this->getHeader('Last-Modified');
    }

    /**
     * @inheritdoc
     */
    public function setLastModified(\DateTime $date = null)
    {
        if (null === $date) {
            $this->removeHeader('Last-Modified');
        }
        else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->setHeader('Last-Modified', $date->format('D, d M Y H:i:s') . ' GMT');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEtag()
    {
        return $this->getHeader('ETag');
    }

    /**
     * @inheritdoc
     */
    public function setEtag($etag = null, $weak = false)
    {
        if (null === $etag) {
            $this->removeHeader('Etag');
        }
        else {
            if (0 !== strpos($etag, '"')) {
                $etag = '"' . $etag . '"';
            }
            $this->setHeader(
                'ETag',
                (true === $weak
                    ? 'W/'
                    : '') . $etag
            );
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCache(array $options)
    {
        if (isset($options['etag'])) {
            $this->setEtag($options['etag']);
        }

        if (isset($options['last_modified'])) {
            $this->setLastModified($options['last_modified']);
        }

        if (isset($options['max_age'])) {
            $this->setMaxAge($options['max_age']);
        }

        if (isset($options['s_maxage'])) {
            $this->setSharedMaxAge($options['s_maxage']);
        }

        if (isset($options['public'])) {
            if ($options['public']) {
                $this->setPublic();
            }
            else {
                $this->setPrivate();
            }
        }

        if (isset($options['private'])) {
            if ($options['private']) {
                $this->setPrivate();
            }
            else {
                $this->setPublic();
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setNotModified()
    {
        $this->setStatusCode(304);
        $this->setContent(null);
        $this->removeHeader('Allow')
             ->removeHeader('Content-Encoding')
             ->removeHeader('Content-Language')
             ->removeHeader('Content-Length')
             ->removeHeader('Content-MD5')
             ->removeHeader('Content-Type')
             ->removeHeader('Last-Modified');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isInvalid()
    {
        return $this->statusCode < 100 || $this->statusCode >= 600;
    }

    /**
     * @inheritdoc
     */
    public function isInformational()
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * @inheritdoc
     */
    public function isRedirection()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * @inheritdoc
     */
    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * @inheritdoc
     */
    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * @inheritdoc
     */
    public function isOk()
    {
        return 200 === $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function isForbidden()
    {
        return 403 === $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function isNotFound()
    {
        return 404 === $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function isRedirect($location = null)
    {
        return in_array(
                   $this->statusCode,
                   [
                       201,
                       301,
                       302,
                       303,
                       307,
                       308,
                   ]
               )
               && (null === $location
            ?: $location == $this->getHeader('Location'));
    }

    /**
     * @inheritdoc
     */
    public function isEmpty()
    {
        return in_array(
            $this->statusCode,
            [
                204,
                304,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function sendHeaders()
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return $this;
        }

        if (!$this->hasHeader('Date')) {
            $this->setDateHeader(\DateTime::createFromFormat('U', time()));
        }

        foreach ($this->getHeaders() as $name => $value) {
            header($name . ': ' . $value, false, $this->statusCode);
        }

        // status
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);

        // cookies
        foreach ($this->getCookies() as $cookie) {
            if ($cookie['raw']) {
                setrawcookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['expire'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httpOnly']
                );
            }
            else {
                setcookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['expire'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httpOnly']
                );
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $this->sendHeaders()
             ->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        elseif ('cli' !== PHP_SAPI) {
            static::closeOutputBuffers(0, true);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function closeOutputBuffers($targetLevel, $flush)
    {
        $status = ob_get_status(true);
        $level = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE')
            ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush
                ? PHP_OUTPUT_HANDLER_FLUSHABLE
                : PHP_OUTPUT_HANDLER_CLEANABLE)
            : -1;

        while ($level-- > $targetLevel && ($s = $status[$level])
               && (!isset($s['del'])
                ? !isset($s['flags']) || $flags === ($s['flags'] & $flags)
                : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            }
            else {
                ob_end_clean();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)
               . "\r\n"
               . $this->headers
               . "\r\n"
               . $this->getContent();
    }
}
