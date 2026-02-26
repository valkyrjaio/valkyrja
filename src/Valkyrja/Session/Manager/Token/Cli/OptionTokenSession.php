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

use JsonException;
use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class OptionTokenSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     * @param non-empty-string      $optionName  The option name
     */
    public function __construct(
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
     *
     * @throws JsonException
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
