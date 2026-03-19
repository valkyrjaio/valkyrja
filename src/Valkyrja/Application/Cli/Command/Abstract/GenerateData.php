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

namespace Valkyrja\Application\Cli\Command\Abstract;

use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Entry\Http;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Formatter\ErrorFormatter;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Formatter\SuccessFormatter;
use Valkyrja\Cli\Interaction\Formatter\WarningFormatter;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Container\Generator\Contract\DataFileGeneratorContract as ContainerDataFileGeneratorContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract as EventDataFileGeneratorContract;
use Valkyrja\Http\Routing\Generator\Contract\DataFileGeneratorContract as HttpDataFileGeneratorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;

abstract class GenerateData
{
    public function __construct(
        protected Env $env,
        protected OutputFactoryContract $outputFactory,
    ) {
    }

    /**
     * Generate the data.
     */
    protected function generateData(): OutputContract
    {
        $output    = $this->getOutput();
        $container = $this->getContainer();

        $output = $this->generateContainerData($container, $output);
        $output = $this->generateEventData($container, $output);
        $output = $this->generateCliData($container, $output);
        $output = $this->generateHttpData($container, $output);

        return $output->withAddedMessages(new NewLine());
    }

    /**
     * Get the output.
     */
    protected function getOutput(): OutputContract
    {
        return $this->outputFactory
            ->createOutput()
            ->withAddedMessages(
                new NewLine(),
                new Message('Generating Data:', new HighlightedTextFormatter()),
                new NewLine(),
                new NewLine(),
            )
            ->writeMessages();
    }

    /**
     * Get the container.
     */
    protected function getContainer(): ContainerContract
    {
        $config = $this->getDebugConfig();
        $app    = Http::app($this->env, $config);

        return $app->getContainer();
    }

    /**
     * Generate the container data.
     */
    protected function generateContainerData(ContainerContract $container, OutputContract $output): OutputContract
    {
        $output = $output->withAddedMessages(
            new Message('Generating Container Data......................'),
        )->writeMessages();

        $dataFileGenerator = $container->getSingleton(ContainerDataFileGeneratorContract::class);
        $status            = $dataFileGenerator->generateFile();

        return $this->addMessagesForGenerateStatus($output, $status)
            ->withAddedMessages(
                new NewLine()
            )
            ->writeMessages();
    }

    /**
     * Generate the event data.
     */
    protected function generateEventData(ContainerContract $container, OutputContract $output): OutputContract
    {
        $output = $output->withAddedMessages(
            new Message('Generating Event Data..........................'),
        )->writeMessages();

        $dataFileGenerator = $container->getSingleton(EventDataFileGeneratorContract::class);
        $status            = $dataFileGenerator->generateFile();

        return $this->addMessagesForGenerateStatus($output, $status)
            ->withAddedMessages(
                new NewLine()
            )
            ->writeMessages();
    }

    /**
     * Generate the cli data.
     */
    protected function generateCliData(ContainerContract $container, OutputContract $output): OutputContract
    {
        $output = $output->withAddedMessages(
            new Message('Generating Cli Routes Data.....................'),
        )->writeMessages();

        $dataFileGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $status            = $dataFileGenerator->generateFile();

        return $this->addMessagesForGenerateStatus($output, $status)
            ->withAddedMessages(
                new NewLine()
            )
            ->writeMessages();
    }

    /**
     * Generate the http data.
     */
    protected function generateHttpData(ContainerContract $container, OutputContract $output): OutputContract
    {
        $output = $output->withAddedMessages(
            new Message('Generating Http Routes Data....................'),
        )->writeMessages();

        $dataFileGenerator = $container->getSingleton(HttpDataFileGeneratorContract::class);
        $status            = $dataFileGenerator->generateFile();

        return $this->addMessagesForGenerateStatus($output, $status)
            ->withAddedMessages(
                new NewLine()
            )
            ->writeMessages();
    }

    /**
     * Add messages for the generate status.
     */
    protected function addMessagesForGenerateStatus(OutputContract $output, GenerateStatus $status): OutputContract
    {
        $text      = 'Failed';
        $formatter = new ErrorFormatter();

        if ($status === GenerateStatus::SUCCESS) {
            $text      = 'Success';
            $formatter = new SuccessFormatter();
        }

        if ($status === GenerateStatus::SKIPPED) {
            $text      = 'Skipped';
            $formatter = new WarningFormatter();
        }

        return $output->withAddedMessages(
            new Message($text, $formatter),
            new NewLine()
        );
    }

    /**
     * Get the debug config.
     */
    abstract protected function getDebugConfig(): Config;
}
