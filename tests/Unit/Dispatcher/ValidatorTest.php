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

namespace Valkyrja\Tests\Unit\Dispatcher;

use Exception;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Dispatcher\Models\Dispatch;
use Valkyrja\Dispatcher\Validators\Validator;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the dispatcher trait.
 *
 * @author Melech Mizrachi
 */
class ValidatorTest extends TestCase
{
    /**
     * A valid static property.
     *
     * @var string
     */
    public static string $validStaticProperty = 'test';

    /**
     * A valid property.
     *
     * @var string
     */
    public string $validProperty = 'test';

    /**
     * A valid property with null value.
     *
     * @var string|null
     */
    public string|null $validPropertyNull = null;
    /**
     * The class to test with.
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected string $value = 'test';

    /**
     * A valid static method.
     *
     * @param string|null $arg [optional] An argument
     *
     * @return string
     */
    public static function validStaticMethod(string|null $arg = null): string
    {
        return 'test' . ($arg ?: '');
    }

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new Validator();
    }

    /**
     * A valid method.
     *
     * @param string|null $arg [optional] An argument
     *
     * @return string
     */
    public function validMethod(string|null $arg = null): string
    {
        return 'test' . ($arg ?: '');
    }

    /**
     * Verify a valid class/method dispatch.
     *
     * @return void
     */
    public function testVerifyClassMethod(): void
    {
        $valid = $this->validator->classMethod(
            (new Dispatch())
                ->setClass(self::class)
                ->setMethod('validMethod')
        ) ?? null;

        self::assertNull($valid);
    }

    /**
     * Verify an invalid class/method dispatch.
     *
     * @return void
     */
    public function testVerifyClassMethodInvalid(): void
    {
        try {
            $this->validator->classMethod(
                (new Dispatch())
                    ->setClass(self::class)
                    ->setMethod('invalidMethod')
            );
        } catch (Exception $exception) {
            self::assertTrue($exception instanceof InvalidMethodException);
        }
    }

    /**
     * Verify a valid class/property dispatch.
     *
     * @return void
     */
    public function testVerifyClassProperty(): void
    {
        $valid = $this->validator->classProperty(
            (new Dispatch())
                ->setClass(self::class)
                ->setProperty('validProperty')
        ) ?? null;

        self::assertNull($valid);
    }

    /**
     * Verify an invalid class/property dispatch.
     *
     * @return void
     */
    public function testVerifyClassPropertyInvalid(): void
    {
        try {
            $this->validator->classProperty(
                (new Dispatch())
                    ->setClass(self::class)
                    ->setProperty('invalidProperty')
            );
        } catch (Exception $exception) {
            self::assertTrue($exception instanceof InvalidPropertyException);
        }
    }

    /**
     * Verify a valid function dispatch.
     *
     * @return void
     */
    public function testVerifyFunction(): void
    {
        $valid = $this->validator->func(
            (new Dispatch())
                ->setFunction('\Valkyrja\routeUrl')
        ) ?? null;

        self::assertNull($valid);
    }

    /**
     * Verify an invalid function dispatch.
     *
     * @return void
     */
    public function testVerifyFunctionInvalid(): void
    {
        try {
            $this->validator->func(
                (new Dispatch())
                    ->setFunction('invalidFunction')
            );
        } catch (Exception $exception) {
            self::assertTrue($exception instanceof InvalidFunctionException);
        }
    }

    /**
     * Verify a valid dispatch.
     *
     * @return void
     */
    public function testVerifyDispatch(): void
    {
        $valid = $this->validator->dispatch(
            (new Dispatch())
                ->setClosure(
                    static function (): void {
                    }
                )
        ) ?? null;

        self::assertNull($valid);
    }

    /**
     * Verify an invalid dispatch.
     *
     * @return void
     */
    public function testVerifyDispatchInvalid(): void
    {
        try {
            $this->validator->dispatch(
                new Dispatch()
            );
        } catch (Exception $exception) {
            self::assertTrue($exception instanceof InvalidDispatchCapabilityException);
        }
    }
}
