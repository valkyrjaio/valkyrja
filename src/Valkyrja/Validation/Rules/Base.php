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

namespace Valkyrja\Validation\Rules;

use Valkyrja\Support\Type\Integer;
use Valkyrja\Support\Type\Str;
use Valkyrja\Validation\Exceptions\ValidationException;

use function is_numeric;

/**
 * Class Base.
 *
 * @author Melech Mizrachi
 */
class Base
{
    /**
     * Subject must have a value.
     *
     * @param mixed $subject The subject
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function required($subject): void
    {
        if (! $subject) {
            throw new ValidationException("${subject} is required");
        }
    }

    /**
     * Subject must equal a given value.
     *
     * @param mixed $subject The subject
     * @param mixed $value   The value
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function equals($subject, $value): void
    {
        if ($subject !== $value) {
            throw new ValidationException("${subject} must equal ${value}");
        }
    }

    /**
     * Subject must be empty.
     *
     * @param string|null $subject [optional] The subject
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function empty(string $subject = null): void
    {
        if ($subject || ! empty($subject)) {
            throw new ValidationException("${subject} must be empty");
        }
    }

    /**
     * Subject must not be empty.
     *
     * @param string $subject The subject
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function notEmpty(string $subject): void
    {
        if (! $subject) {
            throw new ValidationException("${subject} must not be empty");
        }
    }

    /**
     * Subject must be above a minimum length.
     *
     * @param string $subject The subject
     * @param int    $min     [optional] The minimum length
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function min(string $subject, int $min = 0): void
    {
        if (! Str::min($subject, $min)) {
            throw new ValidationException("${subject} must be longer than ${min}");
        }
    }

    /**
     * Subject must be under a maximum length.
     *
     * @param string $subject The subject
     * @param int    $max     [optional] The maximum length
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function max(string $subject, int $max = 255): void
    {
        if (! Str::max($subject, $max)) {
            throw new ValidationException("${subject} must not be longer than ${max}");
        }
    }

    /**
     * Subject must start with a substring.
     *
     * @param string $subject The subject
     * @param string $needle  The needle the subject must start with
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function startsWith(string $subject, string $needle): void
    {
        if (! Str::startsWith($subject, $needle)) {
            throw new ValidationException("${subject} must start with ${needle}");
        }
    }

    /**
     * Subject ends with a substring.
     *
     * @param string $subject The subject
     * @param string $needle  The needle the subject must end with
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function endsWith(string $subject, string $needle): void
    {
        if (! Str::endsWith($subject, $needle)) {
            throw new ValidationException("${subject} must end with ${needle}");
        }
    }

    /**
     * Subject contains a substring.
     *
     * @param string $subject The subject
     * @param string $needle  The needle contained in the subject
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function contains(string $subject, string $needle): void
    {
        if (! Str::contains($subject, $needle)) {
            throw new ValidationException("${subject} must contain ${needle}");
        }
    }

    /**
     * Subject is an email.
     *
     * @param string $subject The subject
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function email(string $subject): void
    {
        if (! Str::isEmail($subject)) {
            throw new ValidationException("${subject} is not a valid email");
        }
    }

    /**
     * Subject is numerical.
     *
     * @param mixed $subject The subject
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function numerical($subject): void
    {
        if (! is_numeric($subject)) {
            throw new ValidationException("${subject} must be numeric");
        }
    }

    /**
     * Subject is less than a maximum value.
     *
     * @param int $subject The subject
     * @param int $max     [optional] The value the subject must be less than
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function lessThan(int $subject, int $max = 0): void
    {
        if (! Integer::lessThan($subject, $max)) {
            throw new ValidationException("${subject} must be less than ${max}");
        }
    }

    /**
     * Subject is more than a minimum value.
     *
     * @param int $subject The subject
     * @param int $min     [optional] The value the subject must be greater than
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function greaterThan(int $subject, int $min = 0): void
    {
        if (! Integer::greaterThan($subject, $min)) {
            throw new ValidationException("${subject} must be greater than ${min}");
        }
    }
}
