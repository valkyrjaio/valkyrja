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

namespace Valkyrja\Cli\Middleware\InputReceived;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Routing\Data\Option\NoInteractionOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\QuietOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\SilentOptionParameter;

class CheckGlobalInteractionOptionsMiddleware implements InputReceivedMiddlewareContract
{
    public function __construct(
        protected Config $config,
        protected Env $env,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function inputReceived(InputContract $input, InputReceivedHandlerContract $handler): InputContract|OutputContract
    {
        $this->setIsInteractive($input);
        $this->setIsQuiet($input);
        $this->setIsSilent($input);

        return $handler->inputReceived($input);
    }

    /**
     * Set the interactivity.
     *
     * @param InputContract $input The input
     */
    protected function setIsInteractive(InputContract $input): void
    {
        if (
            $input->hasOption(NoInteractionOptionParameter::SHORT_NAME)
            || $input->hasOption(NoInteractionOptionParameter::NAME)
        ) {
            $this->config->isInteractive = false;
        }
    }

    /**
     * Set whether output is quiet.
     *
     * @param InputContract $input The input
     */
    protected function setIsQuiet(InputContract $input): void
    {
        if (
            $input->hasOption(QuietOptionParameter::SHORT_NAME)
            || $input->hasOption(QuietOptionParameter::NAME)
        ) {
            $this->config->isQuiet = true;
        }
    }

    /**
     * Set whether the output is entirely silent.
     *
     * @param InputContract $input The input
     */
    protected function setIsSilent(InputContract $input): void
    {
        if (
            $input->hasOption(SilentOptionParameter::SHORT_NAME)
            || $input->hasOption(SilentOptionParameter::NAME)
        ) {
            $this->config->isSilent = true;
        }
    }
}
