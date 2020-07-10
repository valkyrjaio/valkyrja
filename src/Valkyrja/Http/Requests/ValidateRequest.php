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

namespace Valkyrja\Http\Requests;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provides;
use Valkyrja\Http\Request as RequestContract;
use Valkyrja\Http\ValidateRequest as Contract;
use Valkyrja\Validation\Validator;

/**
 * Abstract Class ValidateRequest.
 *
 * @author Melech Mizrachi
 */
abstract class ValidateRequest implements Contract
{
    use Provides;

    /**
     * The request.
     *
     * @var RequestContract
     */
    protected RequestContract $request;

    /**
     * The validator.
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * ValidateRequest constructor.
     *
     * @param RequestContract $request
     * @param Validator       $validator
     */
    public function __construct(RequestContract $request, Validator $validator)
    {
        $this->request   = $request;
        $this->validator = $validator;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $container->setSingleton(
            Contract::class,
            new static(
                $container->getSingleton(RequestContract::class),
                $container->getSingleton(Validator::class)
            )
        );
    }

    /**
     * Validate the request.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->validator->setRules(static::rules());

        return $this->validator->validate();
    }

    /**
     * Get the request.
     *
     * @return RequestContract
     */
    public function getRequest(): RequestContract
    {
        return $this->request;
    }

    /**
     * Get the validator.
     *
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * Get the rules.
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
    abstract protected function rules(): array;
}
