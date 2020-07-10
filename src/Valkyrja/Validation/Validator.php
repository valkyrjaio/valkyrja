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

namespace Valkyrja\Validation;

/**
 * Interface Validator.
 *
 * @author Melech Mizrachi
 */
interface Validator
{
    /**
     * Get a rule set by name.
     *
     * @param string|null $name [optional] The name of the rules to get
     *
     * @return mixed
     */
    public function getRules(string $name = null);

    /**
     * Validate against set rules.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Validate a set of rules.
     *
     * @param array ...$rules The rules
     *
     * @return bool
     */
    public function validateRules(array ...$rules): bool;

    /**
     * Set the rules to validate.
     *
     * <code>
     *      $rules = [
     *          'subjectDescriptor' => [
     *              'subject' => subject,
     *              'rules' => [
     *                  'ruleName' => [
     *                      'arguments'    => [],
     *                      'errorMessage' => 'Unique error message for this rule for this subject.',
     *                  ],
     *              ]
     *          ],
     *          'title' => [
     *              'subject' => $request->getParsedBodyParam('title'),
     *              'rules' => [
     *                  'required' => [
     *                      'arguments'    => [],
     *                      'errorMessage' => 'Title is required.',
     *                  ],
     *                  'notEmpty' => [
     *                      'arguments'    => [],
     *                      'errorMessage' => 'Title must not be empty.',
     *                  ],
     *                  'min' => [
     *                      'arguments'    => [20],
     *                      'errorMessage' => 'Title must be at least 20 characters long.',
     *                  ],
     *                  'max' => [
     *                      'arguments'    => [500],
     *                      'errorMessage' => 'Title must be not be longer than 500 characters.',
     *                  ],
     *                  'unique' => [
     *                      'arguments'    => [Entity::class, 'title'],
     *                      'rules'        => 'ORM',
     *                      'errorMessage' => 'Title must be not be longer than 500 characters.',
     *                  ],
     *              ]
     *          ],
     *      ]
     * </code>
     *
     * @param array ...$rules The rules
     *
     * @return void
     */
    public function setRules(array ...$rules): void;

    /**
     * Get the last error message thrown.
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string;

    /**
     * Set the default error message.
     *
     * @param string $defaultErrorMessage The default error message
     *
     * @return void
     */
    public function setDefaultErrorMessage(string $defaultErrorMessage): void;
}
