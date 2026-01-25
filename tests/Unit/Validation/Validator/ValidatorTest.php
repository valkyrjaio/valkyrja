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
use Valkyrja\Validation\Validator\Contract\ValidatorContract;
use Valkyrja\Validation\Validator\Validator;

class ValidatorTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $validator = new Validator();

        self::assertInstanceOf(ValidatorContract::class, $validator);
    }

    public function testRulesWithValidData(): void
    {
        $validator = new Validator([
            'name' => [new Required('John')],
        ]);

        self::assertTrue($validator->rules());
        self::assertEmpty($validator->getErrorMessages());
    }

    public function testRulesWithInvalidData(): void
    {
        $validator = new Validator([
            'name' => [new Required('')],
        ]);

        self::assertFalse($validator->rules());
        self::assertNotEmpty($validator->getErrorMessages());
    }

    public function testRulesWithPassedRules(): void
    {
        $validator = new Validator();

        $rules = [
            'email' => [new Required('test@test.com')],
        ];

        self::assertTrue($validator->rules($rules));
        self::assertEmpty($validator->getErrorMessages());
    }

    public function testRulesWithPassedInvalidRules(): void
    {
        $validator = new Validator();

        $rules = [
            'email' => [new Required(null)],
        ];

        self::assertFalse($validator->rules($rules));
        self::assertNotEmpty($validator->getErrorMessages());
    }

    public function testSetRules(): void
    {
        $validator = new Validator();

        $rules = [
            'title' => [new Required('Hello')],
        ];

        $validator->setRules($rules);

        self::assertTrue($validator->rules());
    }

    public function testGetErrorMessages(): void
    {
        $validator = new Validator([
            'name'  => [new Required('')],
            'email' => [new Required(null)],
        ]);

        $validator->rules();

        $errors = $validator->getErrorMessages();

        self::assertCount(2, $errors);
        self::assertArrayHasKey('name', $errors);
        self::assertArrayHasKey('email', $errors);
    }

    public function testGetFirstErrorMessage(): void
    {
        $validator = new Validator([
            'name'  => [new Required('')],
            'email' => [new Required(null)],
        ]);

        $validator->rules();

        $firstError = $validator->getFirstErrorMessage();

        self::assertNotNull($firstError);
        self::assertStringContainsString('name:', $firstError);
    }

    public function testGetFirstErrorMessageReturnsNullWhenNoErrors(): void
    {
        $validator = new Validator([
            'name' => [new Required('John')],
        ]);

        $validator->rules();

        self::assertNull($validator->getFirstErrorMessage());
    }

    public function testMultipleRulesPerSubject(): void
    {
        $validator = new Validator([
            'title' => [
                new Required('Hello'),
                new NotEmpty('Hello'),
            ],
        ]);

        self::assertTrue($validator->rules());
    }

    public function testMultipleRulesPerSubjectWithFailure(): void
    {
        $validator = new Validator([
            'title' => [
                new Required(''),
                new NotEmpty(''),
            ],
        ]);

        self::assertFalse($validator->rules());

        // Only one error per subject (first failure)
        $errors = $validator->getErrorMessages();
        self::assertArrayHasKey('title', $errors);
    }

    public function testErrorMessageFormat(): void
    {
        $validator = new Validator([
            'username' => [new Required('')],
        ]);

        $validator->rules();

        $errors = $validator->getErrorMessages();

        self::assertStringStartsWith('username:', $errors['username']);
    }

    public function testEmptyRulesReturnsTrue(): void
    {
        $validator = new Validator([]);

        self::assertTrue($validator->rules());
        self::assertEmpty($validator->getErrorMessages());
    }
}
