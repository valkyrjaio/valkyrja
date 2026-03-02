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

namespace Valkyrja\Tests\Unit\Validation\Validator;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Required;
use Valkyrja\Validation\Throwable\Exception\NoFirstErrorMessageException;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;
use Valkyrja\Validation\Validator\Validator;

final class ValidatorTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $validator = new Validator();

        self::assertInstanceOf(ValidatorContract::class, $validator);
    }

    public function testRulesWithValidData(): void
    {
        $validator = new Validator([
            'name' => [new Required('John', errorMessage: 'Name is required')],
        ]);

        self::assertTrue($validator->validateRules());
        self::assertEmpty($validator->getErrorMessages());
    }

    public function testRulesWithInvalidData(): void
    {
        $validator = new Validator([
            'name' => [new Required('', errorMessage: 'Name is required')],
        ]);

        self::assertFalse($validator->validateRules());
        self::assertNotEmpty($validator->getErrorMessages());
    }

    public function testRulesWithPassedRules(): void
    {
        $validator = new Validator();

        $rules = [
            'email' => [new Required('test@test.com', errorMessage: 'Email is required')],
        ];

        $validator->setRules($rules);

        self::assertTrue($validator->validateRules());
        self::assertEmpty($validator->getErrorMessages());
    }

    public function testRulesWithPassedInvalidRules(): void
    {
        $validator = new Validator();

        $rules = [
            'email' => [new Required(null, errorMessage: 'Email is required')],
        ];

        $validator->setRules($rules);

        self::assertFalse($validator->validateRules());
        self::assertNotEmpty($validator->getErrorMessages());
    }

    public function testSetRules(): void
    {
        $validator = new Validator();

        $rules = [
            'title' => [new Required('Hello', errorMessage: 'Title is required')],
        ];

        $validator->setRules($rules);

        self::assertTrue($validator->validateRules());
    }

    public function testGetErrorMessages(): void
    {
        $validator = new Validator([
            'name'  => [new Required('', errorMessage: 'Name is required')],
            'email' => [new Required(null, errorMessage: 'Email is required')],
        ]);

        $validator->validateRules();

        $errors = $validator->getErrorMessages();

        self::assertCount(2, $errors);
        self::assertArrayHasKey('name', $errors);
        self::assertArrayHasKey('email', $errors);
    }

    public function testGetFirstErrorMessage(): void
    {
        $validator = new Validator([
            'name'  => [new Required('', errorMessage: 'Name is required')],
            'email' => [new Required(null, errorMessage: 'Email is required')],
        ]);

        $validator->validateRules();

        $firstError = $validator->getFirstErrorMessage();

        self::assertNotNull($firstError);
        self::assertStringContainsString('name:', $firstError);
    }

    public function testGetFirstErrorMessageReturnsThrowsWhenNoErrors(): void
    {
        $this->expectException(NoFirstErrorMessageException::class);
        $this->expectExceptionMessage('No error messages');

        $validator = new Validator([
            'name' => [new Required('John', errorMessage: 'Name is required')],
        ]);

        $validator->validateRules();

        self::assertFalse($validator->hasFirstErrorMessage());
        self::assertNull($validator->getFirstErrorMessage());
    }

    public function testMultipleRulesPerSubject(): void
    {
        $validator = new Validator([
            'title' => [
                new Required('Hello', errorMessage: 'Title is required'),
                new NotEmpty('Hello', errorMessage: 'Title cannot be empty'),
            ],
        ]);

        self::assertTrue($validator->validateRules());
    }

    public function testMultipleRulesPerSubjectWithFailure(): void
    {
        $validator = new Validator([
            'title' => [
                new Required('', errorMessage: 'Title is required'),
                new NotEmpty('', errorMessage: 'Title cannot be empty'),
            ],
        ]);

        self::assertFalse($validator->validateRules());

        // Only one error per subject (first failure)
        $errors = $validator->getErrorMessages();
        self::assertArrayHasKey('title', $errors);
    }

    public function testErrorMessageFormat(): void
    {
        $validator = new Validator([
            'username' => [new Required('', errorMessage: 'Username is required')],
        ]);

        $validator->validateRules();

        $errors = $validator->getErrorMessages();

        self::assertStringStartsWith('username:', $errors['username']);
    }

    public function testEmptyRulesReturnsTrue(): void
    {
        $validator = new Validator([]);

        self::assertTrue($validator->validateRules());
        self::assertEmpty($validator->getErrorMessages());
    }
}
