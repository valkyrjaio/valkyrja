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

namespace Valkyrja\Http\Message\Request\Contract;

use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\Param\Contract\AttributeParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ServerParamCollectionContract;

interface ServerRequestContract extends RequestContract
{
    /**
     * Get the server params.
     */
    public function getServerParams(): ServerParamCollectionContract;

    /**
     * Create a new instance with the specified server params.
     */
    public function withServerParams(ServerParamCollectionContract $server): static;

    /**
     * Get the cookie params.
     */
    public function getCookieParams(): CookieParamCollectionContract;

    /**
     * Create a new instance with the specified cookie params.
     */
    public function withCookieParams(CookieParamCollectionContract $cookies): static;

    /**
     * Get the query string arguments.
     */
    public function getQueryParams(): QueryParamCollectionContract;

    /**
     * Create a new instance with the specified query string arguments.
     */
    public function withQueryParams(QueryParamCollectionContract $query): static;

    /**
     * Get the uploaded files.
     */
    public function getUploadedFiles(): UploadedFileCollectionContract;

    /**
     * Create a new instance with the specified uploaded files.
     */
    public function withUploadedFiles(UploadedFileCollectionContract $uploadedFiles): static;

    /**
     * Get the parsed body parameters.
     */
    public function getParsedBody(): ParsedBodyParamCollectionContract;

    /**
     * Create a new instance with the specified parsed body parameters.
     */
    public function withParsedBody(ParsedBodyParamCollectionContract $params): static;

    /**
     * Get the attributes.
     */
    public function getAttributes(): AttributeParamCollectionContract;

    /**
     * Create a new instance with the specified attributes.
     */
    public function withAttributes(AttributeParamCollectionContract $attributes): static;

    /**
     * Is this an AJAX request?
     */
    public function isXmlHttpRequest(): bool;
}
