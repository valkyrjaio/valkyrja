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

namespace Valkyrja\Validation\Rule;

use Override;
use Valkyrja\Validation\Exception\ValidationException;
use Valkyrja\Validation\Rule\Contract\Rule as Contract;

/**
 * Class Rule.
 *
 * @author Melech Mizrachi
 */
abstract class Rule implements Contract
{
    public function __construct(
        protected mixed $subject,
        protected string|null $errorMessage = null
    ) {
    }

    #[Override]
    public function getSubject(): mixed
    {
        return $this->subject;
    }

    #[Override]
    public function validate(): void
    {
        if (! $this->isValid()) {
            $this->getException($this->errorMessage ?? $this->getDefaultErrorMessage());
        }
    }

    protected function getException(string $message): void
    {
        throw new ValidationException($message);
    }

    abstract protected function getDefaultErrorMessage(): string;
}
