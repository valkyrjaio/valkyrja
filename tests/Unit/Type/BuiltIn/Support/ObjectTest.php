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

use JsonException;
use stdClass;
use Valkyrja\Tests\Classes\Model\ModelClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Obj as Helper;
use Valkyrja\Type\Exception\RuntimeException;

use function serialize;

class ObjectTest extends TestCase
{
    protected const string SERIALIZED = 'O:34:"Valkyrja\Tests\Classes\Model\Model":3:{s:55:"Valkyrja\Model\Models\ModelinternalOriginalProperties";a:1:{s:6:"public";s:4:"test";}s:58:"Valkyrja\Model\Models\ModelinternalOriginalPropertiesSet";b:1;s:6:"public";s:4:"test";}';

    /**
     * @throws JsonException
     */
    public function testToString(): void
    {
        $value = new class {
            public string $foo = 'bar';
        };

        self::assertSame('{"foo":"bar"}', Helper::toString($value));
    }

    /**
     * @throws JsonException
     */
    public function testFromString(): void
    {
        $value = Helper::fromString('{"foo":"bar"}');

        self::assertSame('bar', $value->foo);
    }

    /**
     * @throws JsonException
     */
    public function testFromStringInvalidString(): void
    {
        $this->expectException(RuntimeException::class);

        Helper::fromString('"validbutnotobject"');
    }

    public function testToSerializedString(): void
    {
        $value = ModelClass::fromArray(['public' => 'test']);

        self::assertSame(serialize($value), Helper::toSerializedString($value));
    }

    public function testFromSerializedString(): void
    {
        $stdClass      = new stdClass();
        $stdClass->foo = 'bar';

        $serialized = serialize($stdClass);
        $value      = Helper::fromSerializedString($serialized);

        self::assertInstanceOf(stdClass::class, $value);
        self::assertSame('bar', $value->foo);
    }

    public function testFromSerializedStringNotObject(): void
    {
        $this->expectException(RuntimeException::class);

        $serialized = serialize('validstring');

        Helper::fromSerializedString($serialized);
    }

    public function testFromSerializedStringWithNoAllowedClasses(): void
    {
        error_reporting(1);

        $serialized = serialize(ModelClass::fromArray(['public' => 'test']));
        $value      = Helper::fromSerializedString($serialized);

        // No values should be accessible and should all be null
        self::assertNull($value->public);
    }

    public function testFromSerializedStringWithNullAllowedClasses(): void
    {
        $serialized = serialize(ModelClass::fromArray(['public' => 'test']));
        $value      = Helper::fromSerializedString($serialized, null);

        self::assertSame('test', $value->public);
    }

    public function testFromSerializedStringWithAllowedClasses(): void
    {
        $serialized = serialize(ModelClass::fromArray(['public' => 'test']));
        $value      = Helper::fromSerializedString($serialized, [ModelClass::class]);

        self::assertInstanceOf(ModelClass::class, $value);
        self::assertSame('test', $value->public);
    }

    public function testGetAllProperties(): void
    {
        $value = ModelClass::fromArray(['public' => 'test', 'protected' => 'foo', 'private' => 'bar']);
        // Only public properties
        $publicOnlyProperties = Helper::getAllProperties($value, false, false);
        // All public and protected
        $includeProtectedProperties = Helper::getAllProperties($value, includePrivate: false);
        // All public and private
        $includePrivateProperties = Helper::getAllProperties($value, includeProtected: false);
        // All public, protected, and private
        $allProperties = Helper::getAllProperties($value);

        self::assertSame(['public' => 'test'], $publicOnlyProperties);
        self::assertSame(['public' => 'test', 'protected' => 'foo'], $includeProtectedProperties);
        self::assertSame(
            [
                'internalOriginalProperties'    => [
                    'public'    => 'test',
                    'protected' => 'foo',
                    'private'   => 'bar',
                ],
                'internalOriginalPropertiesSet' => true,
                'public'                        => 'test',
                'private'                       => 'bar',
            ],
            $includePrivateProperties
        );
        self::assertSame(
            [
                'internalOriginalProperties'    => [
                    'public'    => 'test',
                    'protected' => 'foo',
                    'private'   => 'bar',
                ],
                'internalOriginalPropertiesSet' => true,
                'public'                        => 'test',
                'protected'                     => 'foo',
                'private'                       => 'bar',
            ],
            $allProperties
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

        $toDeepArray = Helper::toDeepArray($value);

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

        $result        = Helper::getValueDotNotation($value, 'first.second.third.foo', $default);
        $resultDefault = Helper::getValueDotNotation($value, 'first.second.non_existent', $default);

        $resultDefaultNonArray = Helper::getValueDotNotation($value, 'notobject.nonexistent', $default);

        self::assertSame($value->first->second->third->foo, $result);
        self::assertSame($default, $resultDefault);
        self::assertSame($default, $resultDefaultNonArray);
    }
}
