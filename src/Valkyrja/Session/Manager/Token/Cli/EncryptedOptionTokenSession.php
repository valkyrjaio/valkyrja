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

namespace Valkyrja\Session\Manager\Token\Cli;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Crypt\Manager\Contract\CryptContract;

class EncryptedOptionTokenSession extends OptionTokenSession
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $optionName  The option name
     */
    public function __construct(
        protected CryptContract $crypt,
        protected InputContract $input,
        string|null $sessionId = null,
        string|null $sessionName = null,
        protected string $optionName = OptionName::TOKEN
    ) {
        parent::__construct(
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
        parent::setDataFromTokenValue(
            $this->crypt->decrypt($value)
        );
    }
}
