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

use Override;
use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract as Contract;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;

use function in_array;
use function is_callable;
use function sprintf;

/**
 * Class Answer.
 */
class Answer extends Message implements Contract
{
    /** @var non-empty-string */
    protected string $userResponse;

    /** @var non-empty-string[] */
    protected array $allowedResponses = [];

    /**
     * @param non-empty-string                     $defaultResponse    The default response
     * @param callable(non-empty-string):bool|null $validationCallable The validation callable
     * @param non-empty-string                     $text               The text
     * @param non-empty-string[]                   $allowedResponses   The allowed responses
     */
    public function __construct(
        protected string $defaultResponse,
        protected $validationCallable = null,
        protected bool $hasBeenAnswered = false,
        string $text = '%s',
        FormatterContract|null $formatter = null,
        array $allowedResponses = []
    ) {
        if ($this->validationCallable !== null && ! is_callable($this->validationCallable)) {
            throw new InvalidArgumentException('$validationCallable must be a valid callable');
        }

        if (! in_array($defaultResponse, $allowedResponses, true)) {
            $allowedResponses[] = $defaultResponse;
        }

        $this->userResponse     = $defaultResponse;
        $this->allowedResponses = $allowedResponses;

        parent::__construct($text, $formatter);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getText(): string
    {
        return sprintf($this->text, $this->userResponse);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefaultResponse(): string
    {
        return $this->defaultResponse;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDefaultResponse(string $defaultResponse): static
    {
        $new = clone $this;

        if (! $new->hasBeenAnswered) {
            $new->userResponse = $defaultResponse;
        }

        $new->defaultResponse = $defaultResponse;

        if (! in_array($defaultResponse, $new->allowedResponses, true)) {
            $new->allowedResponses[] = $defaultResponse;
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAllowedResponses(): array
    {
        return $this->allowedResponses;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAllowedResponses(string ...$allowedResponses): static
    {
        $new = clone $this;

        $new->allowedResponses = $allowedResponses;

        if (! in_array($new->defaultResponse, $new->allowedResponses, true)) {
            $new->allowedResponses[] = $new->defaultResponse;
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUserResponse(): string
    {
        return $this->userResponse;
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function getValidationCallable(): callable|null
    {
        return $this->validationCallable;
    }

    /**
     * @inheritDoc
     *
     * @param callable(non-empty-string):bool|null $validationCallable The validation callable
     */
    #[Override]
    public function withValidationCallable(callable|null $validationCallable): static
    {
        $new = clone $this;

        $new->validationCallable = $validationCallable;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasBeenAnswered(): bool
    {
        return $this->hasBeenAnswered;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHasBeenAnswered(bool $hasBeenAnswered): static
    {
        $new = clone $this;

        $new->hasBeenAnswered = $hasBeenAnswered;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isValidResponse(): bool
    {
        $validationCallable = $this->validationCallable;
        $userResponse       = $this->userResponse;

        return ($this->allowedResponses === [] && $validationCallable === null)
            || in_array($userResponse, $this->allowedResponses, true)
            || (
                $validationCallable !== null
                && $validationCallable($userResponse)
            );
    }
}
