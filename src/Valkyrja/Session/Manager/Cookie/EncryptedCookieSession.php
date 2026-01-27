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

namespace Valkyrja\Session\Manager\Cookie;

use Override;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

class EncryptedCookieSession extends CookieSession
{
    public function __construct(
        protected CryptContract $crypt,
        protected ServerRequestContract $request,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            request: $request,
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setDataFromCookieValue(string $value): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = $this->crypt->decryptArray($value);
    }

    #[Override]
    protected function getDataAsCookieValue(): string
    {
        return $this->crypt->encryptArray($this->data);
    }
}
