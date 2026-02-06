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

namespace Valkyrja\Tests\Unit\Type\Object\Factory;

use JsonException;
use stdClass;
use Valkyrja\Tests\Classes\Type\Model\ModelClass;
use Valkyrja\Tests\Classes\Type\Object\Support\ObjectFactoryClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\Enum\PropertyVisibilityFilter;
use Valkyrja\Type\Object\Factory\ObjectFactory;
use Valkyrja\Type\Throwable\Exception\RuntimeException;

use function serialize;

class ObjectFactoryTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testToString(): void
    {
        $value = new class {
            public string $foo = 'bar';
        };

        self::assertSame('{"foo":"bar"}', ObjectFactory::toString($value));
    }

    /**
     * @throws JsonException
     */
    public function testFromString(): void
    {
        $value = ObjectFactory::fromString('{"foo":"bar"}');

        self::assertSame('bar', $value->foo);
    }

    /**
     * @throws JsonException
     */
    public function testFromStringInvalidString(): void
    {
        $this->expectException(RuntimeException::class);

        ObjectFactory::fromString('"validbutnotobject"');
    }

    public function testToSerializedString(): void
    {
        $value = ModelClass::fromArray(['public' => 'test']);

        self::assertSame(serialize($value), ObjectFactory::toSerializedString($value));
    }

    public function testFromSerializedString(): void
    {
        $stdClass      = new stdClass();
        $stdClass->foo = 'bar';

        $serialized = serialize($stdClass);
        $value      = ObjectFactory::fromSerializedString($serialized);

        self::assertInstanceOf(stdClass::class, $value);
        self::assertSame('bar', $value->foo);
    }

    public function testFromSerializedStringNotObject(): void
    {
        $this->expectException(RuntimeException::class);

        $serialized = serialize('validstring');

        ObjectFactory::fromSerializedString($serialized);
    }

    public function testFromSerializedStringWithNoAllowedClasses(): void
    {
        error_reporting(1);

        $serialized = serialize(ModelClass::fromArray(['public' => 'test']));
        $value      = ObjectFactory::fromSerializedString($serialized);

        // No values should be accessible and should all be null
        self::assertNull($value->public);
    }

    public function testFromSerializedStringWithNullAllowedClasses(): void
    {
        $serialized = serialize(ModelClass::fromArray(['public' => 'test']));
        $value      = ObjectFactory::fromSerializedString($serialized, null);

        self::assertSame('test', $value->public);
    }

    public function testFromSerializedStringWithAllowedClasses(): void
    {
        $serialized = serialize(ModelClass::fromArray(['public' => 'test']));
        $value      = ObjectFactory::fromSerializedString($serialized, [ModelClass::class]);

        self::assertInstanceOf(ModelClass::class, $value);
        self::assertSame('test', $value->public);
    }

    public function testGetAllProperties(): void
    {
        $value = ModelClass::fromArray(['public' => 'test', 'protected' => 'foo', 'private' => 'bar']);

        // All public, protected, and private
        $allProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::ALL);
        // Only public properties
        $publicOnlyProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::PUBLIC);
        // Only protected properties
        $protectedOnlyProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::PROTECTED);
        // Only private properties
        $privateOnlyProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::PRIVATE);
        // All public and protected
        $publicProtectedProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::PUBLIC_PROTECTED);
        // All public and private
        $publicPrivateProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::PUBLIC_PRIVATE);
        // All protected and private
        $privateProtectedProperties = ObjectFactory::getAllProperties($value, PropertyVisibilityFilter::PRIVATE_PROTECTED);

        self::assertSame(
            [
                'internalShouldSetOriginalProperties' => true,
                'internalOriginalProperties'          => [
                    'public'    => 'test',
                    'protected' => 'foo',
                    'private'   => 'bar',
                ],
                'internalHaveOriginalPropertiesSet'   => true,
                'public'                              => 'test',
                'protected'                           => 'foo',
                'private'                             => 'bar',
            ],
            $allProperties
        );
        self::assertSame(['public' => 'test'], $publicOnlyProperties);
        self::assertSame(
            [
                'internalShouldSetOriginalProperties' => true,
                'internalOriginalProperties'          => [
                    'public'    => 'test',
                    'protected' => 'foo',
                    'private'   => 'bar',
                ],
                'internalHaveOriginalPropertiesSet'   => true,
                'protected'                           => 'foo',
            ],
            $protectedOnlyProperties
        );
        self::assertSame(['private' => 'bar'], $privateOnlyProperties);
        self::assertSame(
            [
                'internalShouldSetOriginalProperties' => true,
                'internalOriginalProperties'          => [
                    'public'    => 'test',
                    'protected' => 'foo',
                    'private'   => 'bar',
                ],
                'internalHaveOriginalPropertiesSet'   => true,
                'public'                              => 'test',
                'protected'                           => 'foo',
            ],
            $publicProtectedProperties
        );
        self::assertSame(
            [
                'public'  => 'test',
                'private' => 'bar',
            ],
            $publicPrivateProperties
        );
        self::assertSame(
            [
                'internalShouldSetOriginalProperties' => true,
                'internalOriginalProperties'          => [
                    'public'    => 'test',
                    'protected' => 'foo',
                    'private'   => 'bar',
                ],
                'internalHaveOriginalPropertiesSet'   => true,
                'protected'                           => 'foo',
                'private'                             => 'bar',
            ],
            $privateProtectedProperties
        );
    }

    /**
     * @throws JsonException
     */
    public function testToDeepArray(): void
    {
        $value = new class {
            public object $first;

            public function __construct()
            {
                $this->first = new class {
                    public object $second;

                    public function __construct()
                    {
                        $this->second = new class {
                            public object $third;

                            public function __construct()
                            {
                                $this->third = new class {
                                    public string $foo = 'bar';
                                };
                            }
                        };
                    }
                };
            }
        };

        $toDeepArray = ObjectFactory::toDeepArray($value);

        self::assertIsArray($toDeepArray['first']);
        self::assertIsArray($toDeepArray['first']['second']);
        self::assertIsArray($toDeepArray['first']['second']['third']);
        self::assertSame('bar', $toDeepArray['first']['second']['third']['foo']);
    }

    public function testGetValueDotNotation(): void
    {
        $value   = new class {
            public object $first;
            public string $notobject = 'test';

            public function __construct()
            {
                $this->first = new class {
                    public object $second;

                    public function __construct()
                    {
                        $this->second = new class {
                            public object $third;

                            public function __construct()
                            {
                                $this->third = new class {
                                    public string $foo = 'bar';
                                };
                            }
                        };
                    }
                };
            }
        };
        $default = 'default';

        $result        = ObjectFactory::getValueDotNotation($value, 'first.second.third.foo', $default);
        $resultDefault = ObjectFactory::getValueDotNotation($value, 'first.second.non_existent', $default);

        $resultDefaultNonArray = ObjectFactory::getValueDotNotation($value, 'notobject.nonexistent', $default);

        self::assertSame($value->first->second->third->foo, $result);
        self::assertSame($default, $resultDefault);
        self::assertSame($default, $resultDefaultNonArray);
    }

    public function testGetAllPropertiesSkipsEmptyKeyAfterSanitization(): void
    {
        $propertyName  = ObjectFactoryClass::exposeSanitizePropertyName("\0test\0", PropertyVisibilityFilter::ALL);
        $propertyName2 = ObjectFactoryClass::exposeSanitizePropertyName('', PropertyVisibilityFilter::ALL);

        self::assertNull($propertyName);
        self::assertNull($propertyName2);
    }
}
