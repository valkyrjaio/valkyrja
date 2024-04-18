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

namespace Valkyrja\Tests\Unit\Type\Support\BuiltIn;

use stdClass;
use Valkyrja\Container\Config\Config;
use Valkyrja\Container\Managers\Container;
use Valkyrja\Model\Model as ModelContract;
use Valkyrja\Tests\Classes\Model\Model;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Exceptions\InvalidClassPropertyProvidedException;
use Valkyrja\Type\Exceptions\InvalidClassProvidedException;
use Valkyrja\Type\Support\Cls as Helper;

class ClassTest extends TestCase
{
    protected string $validProperty = 'test';

    public function testValidateInherits(): void
    {
        $this->expectException(InvalidClassProvidedException::class);

        Helper::validateInherits(self::class, stdClass::class);
    }

    public function testInherits(): void
    {
        self::assertFalse(Helper::inherits(self::class, stdClass::class));
        self::assertTrue(Helper::inherits(self::class, TestCase::class));
    }

    public function testValidateHasProperty(): void
    {
        $this->expectException(InvalidClassPropertyProvidedException::class);

        Helper::validateHasProperty(self::class, 'test');
    }

    public function testHasProperty(): void
    {
        self::assertFalse(Helper::hasProperty(self::class, 'test'));
        self::assertTrue(Helper::hasProperty(self::class, 'validProperty'));
    }

    public function testGetNiceName(): void
    {
        self::assertSame('ValkyrjaTestsUnitTypeSupportBuiltInClassTest', Helper::getNiceName(self::class));
    }

    public function testName(): void
    {
        self::assertSame('ClassTest', Helper::getName(self::class));
    }

    public function testGetDefaultableServiceWithClassNotInContainer(): void
    {
        $publicValue = 'test';
        $container   = new Container(new Config());
        $container->setClosure(
            ModelContract::class,
            /** @param class-string<ModelContract> $name */
            static fn (string $name, mixed ...$args): ModelContract => $name::fromArray($args)
        );

        $object = Helper::getDefaultableService(
            $container,
            Model::class,
            ModelContract::class,
            ['public' => $publicValue]
        );

        self::assertInstanceOf(Model::class, $object);
        self::assertSame($publicValue, $object->public);
    }

    public function testGetDefaultableServiceWithClassInContainer(): void
    {
        $publicValue    = 'test';
        $protectedValue = 'fromModelClosure';
        $container      = new Container(new Config());
        $container->setClosure(
            ModelContract::class,
            /** @param class-string<ModelContract> $name */
            static fn (string $name, mixed ...$args): ModelContract => $name::fromArray($args)
        );
        $container->setClosure(
            Model::class,
            // Setting protected here and not in the ModelContract closure to ensure we're getting this closure back
            // and not the defaultClass specified service
            static fn (mixed ...$args): Model => Model::fromArray([...['protected' => $protectedValue], ...$args])
        );

        $object = Helper::getDefaultableService(
            $container,
            Model::class,
            ModelContract::class,
            ['public' => $publicValue]
        );

        self::assertInstanceOf(Model::class, $object);
        self::assertSame($publicValue, $object->public);
        self::assertSame($protectedValue, $object->protected);
    }
}
