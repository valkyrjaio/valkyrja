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

namespace Valkyrja\Routing;

use BackedEnum;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Model\Data\Cast;
use Valkyrja\Orm\Entity;
use Valkyrja\Routing\Enums\CastType;
use Valkyrja\Routing\Models\Parameter;

/**
 * Interface Route.
 *
 * @author Melech Mizrachi
 */
interface Route extends Dispatch
{
    /**
     * Get the route's path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set the route's path.
     *
     * @param string $path The route path
     *
     * @return static
     */
    public function setPath(string $path): static;

    /**
     * Append a path to the existing path.
     *
     * @param string $path The path to append
     *
     * @return static
     */
    public function withPath(string $path): static;

    /**
     * Append a name to the existing name.
     *
     * @param string $name The name to append
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the redirect path.
     *
     * @return string|null
     */
    public function getTo(): string|null;

    /**
     * Set the redirect path.
     *
     * @param string|null $to The path to redirect to
     *
     * @return static
     */
    public function setTo(string|null $to = null): static;

    /**
     * Get the redirect status code.
     *
     * @return int|null
     */
    public function getCode(): int|null;

    /**
     * Set the redirect status code.
     *
     * @param int|null $code The status code
     *
     * @return static
     */
    public function setCode(int|null $code = null): static;

    /**
     * Get the request methods.
     *
     * @return array
     */
    public function getMethods(): array;

    /**
     * Set the request methods.
     *
     * @param array $methods The request methods
     *
     * @return static
     */
    public function setMethods(array $methods): static;

    /**
     * Get the regex.
     *
     * @return string|null
     */
    public function getRegex(): string|null;

    /**
     * Set the regex.
     *
     * @param string|null $regex The regex
     *
     * @return static
     */
    public function setRegex(string|null $regex = null): static;

    /**
     * Get the parameters.
     *
     * @return array<int, Parameter>
     */
    public function getParameters(): array;

    /**
     * Set the parameters.
     *
     * @param array<int, Parameter>|array<array-key, array<string, mixed>> $parameters The parameters
     *
     * @return static
     */
    public function setParameters(array $parameters): static;

    /**
     * Set a parameter.
     *
     * @param Parameter $parameter The parameter
     *
     * @return static
     */
    public function setParameter(Parameter $parameter): static;

    /**
     * Add a parameter.
     *
     * @param string                        $name                The name
     * @param string|null                   $regex               [optional] The regex
     * @param CastType|null                 $type                [optional] The cast type
     * @param class-string<Entity>|null     $entity              [optional] The entity class name
     * @param string|null                   $entityColumn        [optional] The entity column to query against
     * @param array|null                    $entityRelationships [optional] The entity relationships
     * @param class-string<BackedEnum>|null $enum                [optional] The enum type
     * @param bool                          $isOptional          [optional] Whether the parameter is optional
     * @param bool                          $shouldCapture       [optional] Whether this parameter should be captured
     * @param mixed                         $default             [optional] The default value for this parameter
     *
     * @return static
     */
    public function addParameter(
        string      $name,
        string|null $regex = null,
        Cast|null   $cast = null,
        bool        $isOptional = false,
        bool        $shouldCapture = true,
        mixed       $default = null
    ): static;

    /**
     * Get the middleware.
     *
     * @return array|null
     */
    public function getMiddleware(): array|null;

    /**
     * Set the middleware.
     *
     * @param array|null $middleware The middleware
     *
     * @return static
     */
    public function setMiddleware(array|null $middleware = null): static;

    /**
     * Route with added middleware.
     *
     * @param array $middleware The middleware
     *
     * @return static
     */
    public function withMiddleware(array $middleware): static;

    /**
     * Get the messages.
     *
     * @return class-string<Message>[]|null
     */
    public function getMessages(): array|null;

    /**
     * Set the messages.
     *
     * @param class-string<Message>[]|null $messages The messages
     *
     * @return static
     */
    public function setMessages(array|null $messages = null): static;

    /**
     * Route with added messages.
     *
     * @param class-string<Message>[] $messages The messages
     *
     * @return static
     */
    public function withMessages(array $messages): static;

    /**
     * Route with added message.
     *
     * @param class-string<Message> $message The message
     *
     * @return static
     */
    public function withMessage(string $message): static;

    /**
     * Check whether the route is dynamic.
     *
     * @return bool
     */
    public function isDynamic(): bool;

    /**
     * Set the route as dynamic.
     *
     * @param bool $dynamic Whether the route it dynamic
     *
     * @return static
     */
    public function setDynamic(bool $dynamic = true): static;

    /**
     * Get whether the route is secure.
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Set whether the route is secure.
     *
     * @param bool $secure Whether the route is secure
     *
     * @return static
     */
    public function setSecure(bool $secure = true): static;

    /**
     * Get whether the route is a redirect.
     *
     * @return bool
     */
    public function isRedirect(): bool;

    /**
     * Set whether the route is a redirect.
     *
     * @param bool $redirect
     *
     * @return static
     */
    public function setRedirect(bool $redirect): static;
}
