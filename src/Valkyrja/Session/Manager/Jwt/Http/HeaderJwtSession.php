<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Session\Manager\Jwt\Http;

use Override;
use Valkyrja\Auth\Throwable\Exception\AuthInvalidAuthenticationException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Constant\HeaderValue;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Session\Manager\Abstract\Session;

use function explode;

class HeaderJwtSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $headerName  The header name
     */
    public function __construct(
        protected JwtContract $jwt,
        protected ServerRequestContract $request,
        string|null $sessionId = null,
        string|null $sessionName = null,
        protected string $headerName = HeaderName::AUTHORIZATION
    ) {
        parent::__construct(
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function start(): void
    {
        $headerLine = $this->request->getHeaders()->getHeaderLine($this->headerName);

        if ($headerLine === '') {
            return;
        }

        [$bearer, $token] = explode(' ', $headerLine);

        if ($bearer !== HeaderValue::BEARER || $token === '') {
            throw new AuthInvalidAuthenticationException('Invalid authorization header');
        }

        $this->setDataFromTokenValue($token);
    }

    /**
     * @param non-empty-string $value The token value
     */
    protected function setDataFromTokenValue(string $value): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = $this->jwt->decode($value);
    }
}
