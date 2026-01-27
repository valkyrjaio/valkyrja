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

namespace Valkyrja\Session\Manager\Jwt;

use Override;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

class EncryptedJwtSession extends JwtSession
{
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
