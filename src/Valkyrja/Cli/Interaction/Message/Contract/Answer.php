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

namespace Valkyrja\Cli\Interaction\Message\Contract;

/**
 * Interface Answer.
 *
 * @author Melech Mizrachi
 */
interface Answer extends Message
{
    /**
     * Get the default response.
     *
     * @return non-empty-string
     */
    public function getDefaultResponse(): string;

    /**
     * Create a new Answer with the specified default response.
     *
     * @param non-empty-string $defaultResponse The default response
     *
     * @return static
     */
    public function withDefaultResponse(string $defaultResponse): static;

    /**
     * Get the allowed responses.
     *
     * @return non-empty-string[]
     */
    public function getAllowedResponses(): array;

    /**
     * Create a new Answer with the specified allowed responses.
     *
     * @param non-empty-string ...$allowedResponses The allowed responses
     *
     * @return static
     */
    public function withAllowedResponses(string ...$allowedResponses): static;

    /**
     * Get the user response.
     *
     * @return non-empty-string
     */
    public function getUserResponse(): string;

    /**
     * Create a new Answer with the specified user response.
     *
     * @param non-empty-string $userResponse The user response
     *
     * @return static
     */
    public function withUserResponse(string $userResponse): static;

    /**
     * Get the validation callable.
     *
     * @return callable(non-empty-string):bool|null
     */
    public function getValidationCallable(): callable|null;

    /**
     * Create a new Answer with the specified validation callable.
     *
     * @param callable(non-empty-string):bool|null $validationCallable The validation callable
     *
     * @return static
     */
    public function withValidationCallable(callable|null $validationCallable): static;

    /**
     * Determine whether this Answer was answered.
     *
     * @return bool
     */
    public function hasBeenAnswered(): bool;

    /**
     * Create a new Answer with whether this has been answered.
     *
     * @param bool $hasBeenAnswered Whether the Answer was answered
     *
     * @return static
     */
    public function withHasBeenAnswered(bool $hasBeenAnswered): static;

    /**
     * Determine if the user response is valid.
     *
     * @return bool
     */
    public function isValidResponse(): bool;
}
