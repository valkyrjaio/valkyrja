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
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

class EncryptedHeaderJwtSession extends HeaderJwtSession
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $headerName  The header name
     */
    public function __construct(
        protected CryptContract $crypt,
        protected JwtContract $jwt,
        protected ServerRequestContract $request,
        string|null $sessionId = null,
        string|null $sessionName = null,
        protected string $headerName = HeaderName::AUTHORIZATION
    ) {
        parent::__construct(
            jwt: $jwt,
            request: $request,
            sessionId: $sessionId,
            sessionName: $sessionName,
            headerName: $headerName
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setDataFromTokenValue(string $value): void
    {
        parent::setDataFromTokenValue(
            $this->crypt->decrypt($value)
        );
    }
}
