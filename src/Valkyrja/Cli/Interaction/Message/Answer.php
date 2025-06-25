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

namespace Valkyrja\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Formatter\Contract\Formatter;
use Valkyrja\Cli\Interaction\Message\Contract\Answer as Contract;

/**
 * Class Answer.
 *
 * @author Melech Mizrachi
 */
class Answer extends Message implements Contract
{
    /** @var non-empty-string */
    protected string $userResponse;

    /** @var non-empty-string[] */
    protected array $allowedResponses = [];

    /**
     * @param non-empty-string                     $defaultResponse     The default response
     * @param callable(non-empty-string):bool|null $validationCallable  The validation callable
     * @param non-empty-string                     $text                The text
     * @param non-empty-string                     ...$allowedResponses The allowed responses
     */
    public function __construct(
        protected string $defaultResponse,
        protected $validationCallable = null,
        protected bool $hasBeenAnswered = false,
        string $text = '%s',
        Formatter|null $formatter = null,
        string ...$allowedResponses
    ) {
        if (! is_callable($this->validationCallable)) {
            throw new InvalidArgumentException('$validationCallable must be a valid callable');
        }

        $this->userResponse     = $defaultResponse;
        $this->allowedResponses = $allowedResponses;

        parent::__construct($text, $formatter);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultResponse(): string
    {
        return $this->defaultResponse;
    }

    /**
     * @inheritDoc
     */
    public function withDefaultResponse(string $defaultResponse): static
    {
        $new = clone $this;

        if (! $new->hasBeenAnswered) {
            $new->userResponse = $defaultResponse;
        }

        $new->defaultResponse = $defaultResponse;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getAllowedResponses(): array
    {
        return $this->allowedResponses;
    }

    /**
     * @inheritDoc
     */
    public function withAllowedResponses(string ...$allowedResponses): static
    {
        $new = clone $this;

        $new->allowedResponses = $allowedResponses;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getUserResponse(): string
    {
        return $this->userResponse;
    }

    /**
     * @inheritDoc
     */
    public function withUserResponse(string $userResponse): static
    {
        $new = clone $this;

        $new->userResponse    = $userResponse;
        $new->hasBeenAnswered = true;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getValidationCallable(): callable|null
    {
        return $this->validationCallable;
    }

    /**
     * @inheritDoc
     *
     * @param callable(non-empty-string):bool|null $validationCallable The validation callable
     */
    public function withValidationCallable(callable|null $validationCallable): static
    {
        $new = clone $this;

        $new->validationCallable = $validationCallable;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function hasBeenAnswered(): bool
    {
        return $this->hasBeenAnswered;
    }

    /**
     * @inheritDoc
     */
    public function withHasBeenAnswered(bool $hasBeenAnswered): static
    {
        $new = clone $this;

        $new->hasBeenAnswered = $hasBeenAnswered;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isValidResponse(): bool
    {
        $validationCallable = $this->validationCallable;
        $userResponse       = $this->userResponse;

        return in_array($userResponse, $this->allowedResponses, true)
            || (
                $validationCallable !== null
                && $validationCallable($userResponse)
            );
    }
}
