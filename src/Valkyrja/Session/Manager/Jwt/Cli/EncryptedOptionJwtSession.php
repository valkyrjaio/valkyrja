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
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

class EncryptedOptionJwtSession extends OptionJwtSession
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $optionName  The option name
     */
    public function __construct(
        protected CryptContract $crypt,
        protected JwtContract $jwt,
        protected InputContract $input,
        string|null $sessionId = null,
        string|null $sessionName = null,
        protected string $optionName = OptionName::TOKEN
    ) {
        parent::__construct(
            jwt: $jwt,
            input: $input,
            sessionId: $sessionId,
            sessionName: $sessionName,
            optionName: $optionName
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setDataFromTokenValue(string $value): void
    {
        /** @var non-empty-string $value */
        parent::setDataFromTokenValue(
            $this->crypt->decrypt($value)
        );
    }
}
