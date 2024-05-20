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

namespace Valkyrja\Api;

use Valkyrja\Type\Model\Contract\Model;

/**
 * Interface Json.
 *
 * @author Melech Mizrachi
 */
interface Json extends Model
{
    /**
     * Get the message.
     *
     * @return string|null
     */
    public function getMessage(): string|null;

    /**
     * Set the message.
     *
     * @param string|null $message [optional] The message
     *
     * @return static
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
     *
     * @return static
     */
    public function setErrors(array $errors): static;

    /**
     * Set an error.
     *
     * @param string $error The error
     *
     * @return static
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
     *
     * @return static
     */
    public function setWarnings(array $warnings): static;

    /**
     * Set an warning.
     *
     * @param string $warning The warning
     *
     * @return static
     */
    public function setWarning(string $warning): static;

    /**
     * Get the data.
     *
     * @return array|null
     */
    public function getData(): array|null;

    /**
     * Set the data.
     *
     * @param array|null $data
     *
     * @return static
     */
    public function setData(array|null $data = null): static;

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Set the status code.
     *
     * @param int $statusCode
     *
     * @return static
     */
    public function setStatusCode(int $statusCode): static;

    /**
     * Get the status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set the status.
     *
     * @param string $status
     *
     * @return static
     */
    public function setStatus(string $status): static;
}
