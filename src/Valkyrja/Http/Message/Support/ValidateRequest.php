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

namespace Valkyrja\Http\Message\Support;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Validation\Contract\Validation;

/**
 * Abstract Class ValidateRequest.
 *
 * @author Melech Mizrachi
 */
abstract class ValidateRequest
{
    /**
     * ValidateRequest constructor.
     *
     * @param ServerRequest $request   The request
     * @param Validation    $validator The validator
     */
    public function __construct(
        protected ServerRequest $request,
        protected Validation $validator
    ) {
    }

    /**
     * Validate the request.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->validator->setRules($this->getRules());

        return $this->validator->validate();
    }

    /**
     * Get the request.
     *
     * @return ServerRequest
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * Get the validator.
     *
     * @return Validation
     */
    public function getValidator(): Validation
    {
        return $this->validator;
    }

    /**
     * Get the validation rules.
     *
     * <code>
     *      $rules = [
     *          'title' => [
     *              'subject' => $this->request->getParsedBodyParam('title'),
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
     *              ]
     *          ],
     *      ]
     * </code>
     *
     * @return array
     */
    abstract protected function getRules(): array;
}
