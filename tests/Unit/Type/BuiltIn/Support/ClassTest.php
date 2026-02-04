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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\Support;

use stdClass;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Classes\Type\Model\ModelClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\BuiltIn\Support\Cls;
use Valkyrja\Type\BuiltIn\Throwable\Exception\InvalidClassPropertyProvidedException;
use Valkyrja\Type\BuiltIn\Throwable\Exception\InvalidClassProvidedException;
use Valkyrja\Type\Model\Contract\ModelContract;

class ClassTest extends TestCase
{
    protected string $validProperty = 'test';

    public function testValidateInherits(): void
    {
        $this->expectException(InvalidClassProvidedException::class);

        Cls::validateInherits(self::class, stdClass::class);
    }

    public function testInherits(): void
    {
        self::assertFalse(Cls::inherits(self::class, stdClass::class));
        self::assertTrue(Cls::inherits(self::class, TestCase::class));
    }

    public function testValidateHasProperty(): void
    {
        $this->expectException(InvalidClassPropertyProvidedException::class);

        Cls::validateHasProperty(self::class, 'test');
    }

    public function testHasProperty(): void
    {
        self::assertFalse(Cls::hasProperty(self::class, 'test'));
        self::assertTrue(Cls::hasProperty(self::class, 'validProperty'));
    }

    public function testGetNiceName(): void
    {
        self::assertSame('ValkyrjaTestsUnitTypeBuiltInSupportClassTest', Cls::getNiceName(self::class));
    }

    public function testName(): void
    {
        self::assertSame('ClassTest', Cls::getName(self::class));
    }

    public function testGetDefaultableServiceWithClassNotInContainer(): void
    {
        $publicValue = 'test';
        $container   = new Container();
        $container->setCallable(
            ModelContract::class,
            /** @param class-string<ModelContract> $name */
            static fn (Container $container, string $name, mixed ...$args): ModelContract => $name::fromArray($args)
        );

        $object = Cls::getDefaultableService(
            $container,
            ModelClass::class,
            ModelContract::class,
            ['public' => $publicValue]
        );

        self::assertInstanceOf(ModelClass::class, $object);
        self::assertSame($publicValue, $object->public);
    }

    public function testGetDefaultableServiceWithClassInContainer(): void
    {
        $publicValue    = 'test';
        $protectedValue = 'fromModelClosure';
        $container      = new Container();
        $container->setCallable(
            ModelContract::class,
            /** @param class-string<ModelContract> $name */
            static fn (Container $container, string $name, mixed ...$args): ModelContract => $name::fromArray($args)
        );
        $container->setCallable(
            ModelClass::class,
            // Setting protected here and not in the ModelContract closure to ensure we're getting this closure back
            // and not the defaultClass specified service
            static fn (Container $container, mixed ...$args): ModelClass => ModelClass::fromArray([...['protected' => $protectedValue], ...$args])
        );

        $object = Cls::getDefaultableService(
            $container,
            ModelClass::class,
            ModelContract::class,
            ['public' => $publicValue]
        );

        self::assertInstanceOf(ModelClass::class, $object);
        self::assertSame($publicValue, $object->public);
        self::assertSame($protectedValue, $object->protected);
    }
}
