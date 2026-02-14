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

namespace Valkyrja\Http\Message\Request;

use Override;
use Valkyrja\Http\Message\Constant\ContentTypeValue;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ParsedJsonParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ServerParamCollectionContract;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\ParsedJsonParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class JsonServerRequest extends ServerRequest implements JsonServerRequestContract
{
    protected bool $hadParsedBody = true;

    public function __construct(
        UriContract $uri = new Uri(),
        RequestMethod $method = RequestMethod::GET,
        StreamContract $body = new Stream(stream: PhpWrapper::input),
        HeaderCollectionContract $headers = new HeaderCollection(),
        ProtocolVersion $protocol = ProtocolVersion::V1_1,
        protected ServerParamCollectionContract $server = new ServerParamCollection(),
        protected CookieParamCollectionContract $cookies = new CookieParamCollection(),
        protected QueryParamCollectionContract $query = new QueryParamCollection(),
        protected ParsedBodyParamCollectionContract $parsedBody = new ParsedBodyParamCollection(),
        protected ParsedJsonParamCollectionContract $parsedJson = new ParsedJsonParamCollection(),
        protected UploadedFileCollectionContract $files = new UploadedFileCollection()
    ) {
        parent::__construct(
            uri: $uri,
            method: $method,
            body: $body,
            headers: $headers,
            protocol: $protocol,
            server: $server,
            cookies: $cookies,
            query: $query,
            parsedBody: $parsedBody,
            files: $files
        );

        $contentType = $headers->get(name: HeaderName::CONTENT_TYPE)?->getValuesAsString();

        if (
            $contentType !== null
            && str_contains($contentType, ContentTypeValue::APPLICATION_JSON)
        ) {
            $bodyContents = (string) $body;

            if (empty($bodyContents)) {
                return;
            }

            $this->parsedJson = ParsedJsonParamCollection::fromArray(ArrayFactory::fromString($bodyContents));

            if (empty($parsedBody->getAll())) {
                $this->hadParsedBody = false;
            }
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getParsedJson(): ParsedJsonParamCollectionContract
    {
        return $this->parsedJson;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withParsedJson(ParsedJsonParamCollectionContract $params): static
    {
        $new = clone $this;

        $new->parsedJson = $params;

        return $new;
    }
}
