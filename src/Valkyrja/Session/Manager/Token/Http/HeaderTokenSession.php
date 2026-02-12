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

namespace Valkyrja\Session\Manager\Token\Http;

use JsonException;
use Override;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Constant\HeaderValue;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class HeaderTokenSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $headerName  The header name
     */
    public function __construct(
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
     *
     * @throws JsonException
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
            throw new InvalidAuthenticationException('Invalid authorization header');
        }

        $this->setDataFromTokenValue($token);
    }

    /**
     * @param non-empty-string $value The token value
     *
     * @throws JsonException
     */
    protected function setDataFromTokenValue(string $value): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = ArrayFactory::fromString($value);
    }
}
