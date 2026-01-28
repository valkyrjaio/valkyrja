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

namespace Valkyrja\Api\Model\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Type\Model\Contract\ModelContract;

interface JsonContract extends ModelContract
{
    /**
     * Get the message.
     */
    public function getMessage(): string|null;

    /**
     * Set the message.
     *
     * @param string|null $message [optional] The message
     */
    public function setMessage(string|null $message = null): static;

    /**
     * Get the errors.
     *
     * @return string[]
     */
    public function getErrors(): array;

    /**
     * Set the errors.
     *
     * @param string[] $errors The errors
     */
    public function setErrors(array $errors): static;

    /**
     * Set an error.
     *
     * @param string $error The error
     */
    public function setError(string $error): static;

    /**
     * Get the warnings.
     *
     * @return string[]
     */
    public function getWarnings(): array;

    /**
     * Set the warnings.
     *
     * @param string[] $warnings The warnings
     */
    public function setWarnings(array $warnings): static;

    /**
     * Set an warning.
     *
     * @param string $warning The warning
     */
    public function setWarning(string $warning): static;

    /**
     * Get the data.
     *
     * @return array<string, mixed>|null
     */
    public function getData(): array|null;

    /**
     * Set the data.
     *
     * @param array<string, mixed>|null $data
     */
    public function setData(array|null $data = null): static;

    /**
     * Get the status code.
     */
    public function getStatusCode(): StatusCode;

    /**
     * Set the status code.
     */
    public function setStatusCode(StatusCode $statusCode): static;

    /**
     * Get the status.
     */
    public function getStatus(): string;

    /**
     * Set the status.
     */
    public function setStatus(string $status): static;
}
