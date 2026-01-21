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

namespace Valkyrja\Cli\Server\Middleware\InputReceived;

use Override;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

class CheckGlobalInteractionOptionsMiddleware implements InputReceivedMiddlewareContract
{
    /**
     * @param non-empty-string $noInteractionOptionName      The no interaction option name
     * @param non-empty-string $noInteractionOptionShortName The no interaction option short name
     * @param non-empty-string $quietOptionName              The quiet option name
     * @param non-empty-string $quietOptionShortName         The quiet option short name
     * @param non-empty-string $silentOptionName             The silent option name
     * @param non-empty-string $silentOptionShortName        The silent option short name
     */
    public function __construct(
        protected Config $config,
        protected string $noInteractionOptionName,
        protected string $noInteractionOptionShortName,
        protected string $quietOptionName,
        protected string $quietOptionShortName,
        protected string $silentOptionName,
        protected string $silentOptionShortName,
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
            $input->hasOption($this->noInteractionOptionShortName)
            || $input->hasOption($this->noInteractionOptionName)
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
            $input->hasOption($this->quietOptionShortName)
            || $input->hasOption($this->quietOptionName)
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
            $input->hasOption($this->silentOptionShortName)
            || $input->hasOption($this->silentOptionName)
        ) {
            $this->config->isSilent = true;
        }
    }
}
