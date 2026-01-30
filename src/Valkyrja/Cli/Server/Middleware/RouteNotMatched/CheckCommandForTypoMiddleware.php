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

namespace Valkyrja\Cli\Server\Middleware\RouteNotMatched;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;

use function array_filter;
use function array_key_first;
use function array_map;
use function similar_text;

class CheckCommandForTypoMiddleware implements RouteNotMatchedMiddlewareContract
{
    protected RouteContract|null $matchedRoute = null;

    /**
     * @param non-empty-string $defaultAnswer The default answer
     */
    public function __construct(
        protected RouterContract $router,
        protected CollectionContract $collection,
        protected string $defaultAnswer = 'no',
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routeNotMatched(InputContract $input, OutputContract $output, RouteNotMatchedHandlerContract $handler): OutputContract
    {
        $routeOrOutput = $this->checkCommandNameForTypo($input, $output);

        if ($routeOrOutput instanceof RouteContract) {
            $output = $this->router->dispatch(
                input: $input->withCommandName($routeOrOutput->getName())
            );
        }

        return $handler->routeNotMatched($input, $output);
    }

    /**
     * Check the command name from the input for a typo.
     */
    protected function checkCommandNameForTypo(InputContract $input, OutputContract $output): RouteContract|OutputContract
    {
        $name = $input->getCommandName();

        $commands = [];

        foreach ($this->collection->all() as $command) {
            similar_text($command->getName(), $name, $percent);

            if ($percent >= 60) {
                $commands[] = $command;
            }
        }

        if ($commands !== []) {
            return $this->askToRunSimilarCommands($output, $commands);
        }

        return $output;
    }

    /**
     * Ask the user if they want to run similar commands.
     *
     * @param RouteContract[] $commands The list of commands
     */
    protected function askToRunSimilarCommands(OutputContract $output, array $commands): RouteContract|OutputContract
    {
        $commandNames = array_map(static fn (RouteContract $command) => $command->getName(), $commands);

        $output = $output
            ->withAddedMessages(
                new NewLine(),
                new Question(
                    'Did you mean to run one of the following commands?',
                    fn (OutputContract $output, AnswerContract $answer): OutputContract => $this->questionCallback(
                        output: $output,
                        answer: $answer,
                        commands: $commands
                    ),
                    new Answer(
                        defaultResponse: $this->defaultAnswer,
                        allowedResponses: $commandNames
                    ),
                ),
            )
            ->writeMessages();

        return $this->matchedRoute
            ?? $output;
    }

    /**
     * @param RouteContract[] $commands The list of commands
     */
    protected function questionCallback(OutputContract $output, AnswerContract $answer, array $commands): OutputContract
    {
        $response           = $answer->getUserResponse();
        $this->matchedRoute = $response !== 'no'
            ? $this->getMatchedRoute(commands: $commands, response: $response)
            : null;

        return $output;
    }

    /**
     * @param RouteContract[] $commands The list of commands
     */
    protected function getMatchedRoute(array $commands, string $response): RouteContract|null
    {
        $matchedRoutes = array_filter(
            $commands,
            static fn (RouteContract $command): bool => $command->getName() === $response
        );

        if ($matchedRoutes !== []) {
            return $matchedRoutes[array_key_first($matchedRoutes)];
        }

        return null;
    }
}
