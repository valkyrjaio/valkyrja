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

namespace Valkyrja\Validation\Validator\Contract;

use Valkyrja\Validation\Rule\Contract\RuleContract;

interface ValidatorContract
{
    /**
     * Validate a set of rules.
     *
     * @param array<non-empty-string, RuleContract[]>|null $rules The rules
     */
    public function rules(array|null $rules = null): bool;

    /**
     * Set the rules to validate.
     *
     * <code>
     *      $rules = [
     *          'subjectDescriptor' => [
     *              new Rule($subject),
     *          ],
     *          'title' => [
     *               new Required($title = $request->getParsedBodyParam('title'), 'Title is required'),
     *               new NotEmpty($title, 'Title cannot be empty'),
     *               new Min($title, 20, 'Title must be at least 20 characters long'),
     *               new Max($title, 200, 'Title must not be longer than 200 characters'),
     *          ],
     *      ]
     * </code>
     *
     * @param array<non-empty-string, RuleContract[]> $rules The rules
     */
    public function setRules(array $rules): void;

    /**
     * Get the error messages.
     *
     * @return array<non-empty-string, non-empty-string>
     */
    public function getErrorMessages(): array;

    /**
     * Get the first error message thrown.
     *
     * @return non-empty-string|null
     */
    public function getFirstErrorMessage(): string|null;
}
