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

namespace Valkyrja\Test\Assert;

use Throwable;
use Valkyrja\Test\Assert\Contract\Exceptions as Contract;
use Valkyrja\Test\Exception\AssertFailureException;

/**
 * Class Exceptions.
 *
 * @author Melech Mizrachi
 */
class Exceptions extends Asserter implements Contract
{
    /**
     * Whether an exception is expected to be thrown.
     */
    protected bool $expecting = false;

    /**
     * The expected class name.
     *
     * @var class-string
     */
    protected string $className;

    /**
     * The expected message.
     */
    protected string $message;

    /**
     * @inheritDoc
     */
    public static function getExpectedErrorMessage(): string
    {
        return 'An exception was expected. Got none.';
    }

    /**
     * @inheritDoc
     */
    public static function getUnexpectedErrorMessage(string $actualClassName, string $actualMessage): string
    {
        return "An unexpected exception $actualClassName with message $actualMessage was thrown.";
    }

    /**
     * @inheritDoc
     */
    public static function getIncorrectClassNameErrorMessage(string $expected, string $actual): string
    {
        return "Expected $expected exception does not match actual $actual.";
    }

    /**
     * @inheritDoc
     */
    public static function getIncorrectMessageErrorMessage(string $expected, string $actual): string
    {
        return "Expected $expected message does not match actual $actual.";
    }

    /**
     * @inheritDoc
     */
    public function className(string $className): void
    {
        $this->assertions[] = $className;

        $this->className = $className;

        $this->expecting();
    }

    /**
     * @inheritDoc
     */
    public function message(string $message): void
    {
        $this->assertions[] = $message;

        $this->message = $message;

        $this->expecting();
    }

    /**
     * @inheritDoc
     */
    public function expecting(): void
    {
        $this->expecting = true;
    }

    /**
     * @inheritDoc
     */
    public function verify(Throwable|null $exception = null): void
    {
        if ($exception === null) {
            if ($this->expecting) {
                $this->errors[] = new AssertFailureException(self::getExpectedErrorMessage());
            }

            return;
        }

        $actualClassName = $exception::class;
        $actualMessage   = $exception->getMessage();

        if (! $this->expecting) {
            $this->errors[] = new AssertFailureException(
                self::getUnexpectedErrorMessage($actualClassName, $actualMessage)
            );

            return;
        }

        /**
         * @psalm-suppress RedundantConditionGivenDocblockType
         */
        if (isset($this->className) && ($className = $this->className) !== $actualClassName) {
            $this->errors[] = new AssertFailureException(
                self::getIncorrectClassNameErrorMessage($className, $actualClassName)
            );
        }

        /**
         * @psalm-suppress RedundantCondition
         */
        if (isset($this->message) && ($message = $this->message) !== $actualMessage) {
            $this->errors[] = new AssertFailureException(
                self::getIncorrectMessageErrorMessage($message, $actualMessage)
            );
        }
    }
}
