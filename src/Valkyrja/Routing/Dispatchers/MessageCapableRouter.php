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

namespace Valkyrja\Routing\Dispatchers;

use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Event\Dispatcher as Events;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Config\Config;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Message;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Abort;
use Valkyrja\Validation\Validator;

use function assert;

/**
 * Class MessageCapableRouter.
 *
 * @author Melech Mizrachi
 */
class MessageCapableRouter extends Router
{
    /**
     * MessageCapableRouter constructor.
     */
    public function __construct(
        protected Validator $validator,
        Collection $collection,
        Container $container,
        Dispatcher $dispatcher,
        Events $events,
        Matcher $matcher,
        ResponseFactory $responseFactory,
        Config|array $config,
        bool $debug = false
    ) {
        parent::__construct(
            collection: $collection,
            container: $container,
            dispatcher: $dispatcher,
            events: $events,
            matcher: $matcher,
            responseFactory: $responseFactory,
            config: $config,
            debug: $debug
        );
    }

    /**
     * @inheritDoc
     */
    public function getRouteFromRequest(Request $request): Route
    {
        $route = parent::getRouteFromRequest($request);

        foreach ($route->getMessages() ?? [] as $message) {
            $this->ensureIsMessage($message);
            $this->ensureRequestConformsToMessage($request, $message);
        }

        return $route;
    }

    /**
     * Ensure a message is a message.
     *
     * @param string $message The message
     *
     * @return void
     */
    protected function ensureIsMessage(string $message): void
    {
        assert($this->determineIsMessage($message));
    }

    /**
     * Determine if a dependency is a message.
     *
     * @param string $message The message
     *
     * @return bool
     */
    protected function determineIsMessage(string $message): bool
    {
        return is_a($message, Message::class, true);
    }

    /**
     * @param Request               $request The request
     * @param class-string<Message> $message The message class name
     *
     * @return void
     */
    protected function ensureRequestConformsToMessage(Request $request, string $message): void
    {
        $this->ensureRequestHasNoExtraData($request, $message);
        $this->ensureRequestIsValid($request, $message);
    }

    /**
     * @param Request               $request The request
     * @param class-string<Message> $message The message class name
     *
     * @return void
     */
    protected function ensureRequestHasNoExtraData(Request $request, string $message): void
    {
        // If there is extra data
        if ($message::determineIfRequestContainsExtraData($request)) {
            // Then the payload is too large
            $this->abortDueToExtraData($request, $message);
        }
    }

    /**
     * @param Request               $request The request
     * @param class-string<Message> $message The message class name
     *
     * @return void
     */
    protected function abortDueToExtraData(Request $request, string $message): void
    {
        Abort::abort413();
    }

    /**
     * @param Request               $request The request
     * @param class-string<Message> $message The message class name
     *
     * @return void
     */
    protected function ensureRequestIsValid(Request $request, string $message): void
    {
        if (($messageRules = $message::getValidationRules()) === null || empty($messageRules)) {
            return;
        }

        $validator = $this->validator;
        $validator->setRules($this->getValidatorRules($message::getDataFromRequest($request), $messageRules));

        if (! $validator->validate()) {
            $this->abortDueToValidationErrors($request, $message);
        }
    }

    /**
     * @param Request               $request The request
     * @param class-string<Message> $message The message class name
     *
     * @return void
     */
    protected function abortDueToValidationErrors(Request $request, string $message): void
    {
        Abort::abort400();
    }

    /**
     * @param array                                                                   $data         The data
     * @param array<string, array<string, array{arguments: array, message?: string}>> $messageRules The message rules
     *
     * @return array
     */
    protected function getValidatorRules(array $data, array $messageRules): array
    {
        $rules = [];

        foreach ($messageRules as $param => $paramRule) {
            $rules[$param] = [
                'subject' => $data[$param],
                'rules'   => $paramRule,
            ];
        }

        return $rules;
    }
}
