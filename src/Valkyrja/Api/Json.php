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

use Valkyrja\Model\Model;

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
    public function getMessage(): ?string;

    /**
     * Set the message.
     *
     * @param string|null $message [optional] The message
     *
     * @return static
     */
    public function setMessage(string $message = null): self;

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
    public function setErrors(array $errors): self;

    /**
     * Set an error.
     *
     * @param string $error The error
     *
     * @return static
     */
    public function setError(string $error): self;

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
    public function setWarnings(array $warnings): self;

    /**
     * Set an warning.
     *
     * @param string $warning The warning
     *
     * @return static
     */
    public function setWarning(string $warning): self;

    /**
     * Get the data.
     *
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * Set the data.
     *
     * @param array|null $data
     *
     * @return static
     */
    public function setData(array $data = null): self;

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
    public function setStatusCode(int $statusCode): self;

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
    public function setStatus(string $status): self;
}
