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

interface AnswerContract extends MessageContract
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
     */
    public function withUserResponse(string $userResponse): static;

    /**
     * Determine if there is a validation callable.
     */
    public function hasValidationCallable(): bool;

    /**
     * Get the validation callable.
     *
     * @return callable(non-empty-string):bool
     */
    public function getValidationCallable(): callable;

    /**
     * Create a new Answer with the specified validation callable.
     *
     * @param callable(non-empty-string):bool $validationCallable The validation callable
     */
    public function withValidationCallable(callable $validationCallable): static;

    /**
     * Create a new Answer without a validation callable.
     */
    public function withoutValidationCallable(): static;

    /**
     * Determine whether this Answer was answered.
     */
    public function hasBeenAnswered(): bool;

    /**
     * Create a new Answer with whether this has been answered.
     *
     * @param bool $hasBeenAnswered Whether the Answer was answered
     */
    public function withHasBeenAnswered(bool $hasBeenAnswered): static;

    /**
     * Determine if the user response is valid.
     */
    public function isValidResponse(): bool;
}
