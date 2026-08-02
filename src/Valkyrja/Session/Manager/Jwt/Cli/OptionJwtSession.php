<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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

        if ($option === null || ! $option->hasValue()) {
            return;
        }

        $token  = $option->getValue();

        $this->setDataFromTokenValue($token);
    }

    /**
     * Set the data from a token value.
     */
    protected function setDataFromTokenValue(string $value): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = $this->jwt->decode($value);
    }
}
