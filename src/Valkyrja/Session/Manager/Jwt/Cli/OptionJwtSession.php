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

namespace Valkyrja\Session\Manager\Jwt\Cli;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Session\Manager\Abstract\Session;

class OptionJwtSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $optionName  The option name
     */
    public function __construct(
        protected JwtContract $jwt,
        protected InputContract $input,
        string|null $sessionId = null,
        string|null $sessionName = null,
        protected string $optionName = OptionName::TOKEN
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
        $option = $this->input->getOption($this->optionName)[0] ?? null;
        $token  = $option?->getValue();

        if ($token === null) {
            return;
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
