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

namespace Valkyrja\Tests\Unit\Orm\Entity\Abstract;

use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Tests\Classes\Orm\Entity\EntityClass;
use Valkyrja\Tests\Classes\Orm\Entity\EntityIntIdClass;
use Valkyrja\Tests\Classes\Orm\Entity\EntityStringIdClass;
use Valkyrja\Tests\Classes\Orm\Entity\EntityWithAllFeaturesClass;
use Valkyrja\Tests\Classes\Orm\Entity\EntityWithCastingsClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Throwable\Exception\RuntimeException;
use Valkyrja\Type\BuiltIn\IntT;

class EntityTest extends TestCase
{
    public function testImplementsEntityContract(): void
    {
        $entity = new EntityIntIdClass();

        self::assertInstanceOf(EntityContract::class, $entity);
    }

    public function testGetTableName(): void
    {
        self::assertSame('test', EntityIntIdClass::getTableName());
        self::assertSame('entities_with_features', EntityWithAllFeaturesClass::getTableName());
    }

    public function testGetIdFieldReturnsDefaultId(): void
    {
        self::assertSame('id', EntityIntIdClass::getIdField());
    }

    public function testGetIdFieldReturnsCustomId(): void
    {
        self::assertSame('entity_id', EntityWithAllFeaturesClass::getIdField());
    }

    public function testGetRepositoryReturnsNullByDefault(): void
    {
        self::assertNull(EntityIntIdClass::getRepository());
    }

    public function testGetRepositoryReturnsCustomRepository(): void
    {
        self::assertSame(Repository::class, EntityWithAllFeaturesClass::getRepository());
    }

    public function testGetRelationshipPropertiesReturnsEmptyArrayByDefault(): void
    {
        self::assertSame([], EntityIntIdClass::getRelationshipProperties());
    }

    public function testGetRelationshipPropertiesReturnsConfiguredProperties(): void
    {
        self::assertSame(['relatedEntity'], EntityWithAllFeaturesClass::getRelationshipProperties());
    }

    public function testGetUnStorableFieldsReturnsEmptyArrayByDefault(): void
    {
        self::assertSame([], EntityIntIdClass::getUnStorableFields());
    }

    public function testGetUnStorableFieldsReturnsConfiguredFields(): void
    {
        self::assertSame(['tempField'], EntityWithAllFeaturesClass::getUnStorableFields());
    }

    public function testGetIdValueWithIntId(): void
    {
        $entity     = EntityIntIdClass::fromArray(['id' => 42, 'name' => 'Test']);
        $entity->id = 42;

        self::assertSame(42, $entity->getIdValue());
    }

    public function testGetIdValueWithStringId(): void
    {
        $entity     = EntityStringIdClass::fromArray(['id' => 'uuid-123', 'name' => 'Test']);
        $entity->id = 'uuid-123';

        self::assertSame('uuid-123', $entity->getIdValue());
    }

    public function testGetIdValueWithCustomIdField(): void
    {
        $entity            = EntityWithAllFeaturesClass::fromArray(['entity_id' => 99, 'name' => 'Test']);
        $entity->entity_id = 99;

        self::assertSame(99, $entity->getIdValue());
    }

    public function testGetIdValueThrowsExceptionForEmptyStringId(): void
    {
        $entity     = EntityStringIdClass::fromArray(['id' => '', 'name' => 'Test']);
        $entity->id = '';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Id field value should be a string or int');

        $entity->getIdValue();
    }

    public function testFromArrayCreatesEntity(): void
    {
        $entity = EntityIntIdClass::fromArray(['id' => 1, 'name' => 'Test Entity']);

        self::assertInstanceOf(EntityIntIdClass::class, $entity);
        self::assertSame(1, $entity->id);
        self::assertSame('Test Entity', $entity->name);
    }

    public function testAsStorableArrayReturnsAllPublicProperties(): void
    {
        $entity       = EntityIntIdClass::fromArray(['id' => 1, 'name' => 'Test']);
        $entity->id   = 1;
        $entity->name = 'Test';

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('id', $storable);
        self::assertArrayHasKey('name', $storable);
        self::assertSame(1, $storable['id']);
        self::assertSame('Test', $storable['name']);
    }

    public function testAsStorableArrayExcludesUnStorableFields(): void
    {
        $entity              = EntityWithAllFeaturesClass::fromArray(['entity_id' => 1, 'name' => 'Test']);
        $entity->entity_id   = 1;
        $entity->name        = 'Test';
        $entity->tempField   = 'should be excluded';

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('entity_id', $storable);
        self::assertArrayHasKey('name', $storable);
        self::assertArrayNotHasKey('tempField', $storable);
    }

    public function testAsStorableArrayExcludesRelationshipProperties(): void
    {
        $entity                = EntityWithAllFeaturesClass::fromArray(['entity_id' => 1, 'name' => 'Test']);
        $entity->entity_id     = 1;
        $entity->name          = 'Test';
        $entity->relatedEntity = ['some' => 'data'];

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('entity_id', $storable);
        self::assertArrayHasKey('name', $storable);
        self::assertArrayNotHasKey('relatedEntity', $storable);
    }

    public function testAsStorableArrayWithSpecificProperties(): void
    {
        $entity       = EntityIntIdClass::fromArray(['id' => 1, 'name' => 'Test']);
        $entity->id   = 1;
        $entity->name = 'Test';

        $storable = $entity->asStorableArray('name');

        self::assertArrayNotHasKey('id', $storable);
        self::assertArrayHasKey('name', $storable);
        self::assertSame('Test', $storable['name']);
    }

    public function testAsStorableChangedArrayReturnsOnlyChangedProperties(): void
    {
        $entity       = EntityIntIdClass::fromArray(['id' => 1, 'name' => 'Original']);
        $entity->name = 'Changed';

        $changed = $entity->asStorableChangedArray();

        self::assertArrayHasKey('name', $changed);
        self::assertSame('Changed', $changed['name']);
    }

    public function testAsStorableChangedArrayExcludesUnchangedProperties(): void
    {
        $entity = EntityIntIdClass::fromArray(['id' => 1, 'name' => 'Original']);
        // Not changing name

        $changed = $entity->asStorableChangedArray();

        self::assertArrayNotHasKey('name', $changed);
    }

    public function testEntityWithNullableProperty(): void
    {
        $entity              = EntityClass::fromArray(['id' => 1]);
        $entity->id          = 1;
        $entity->property    = null;

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('property', $storable);
        self::assertNull($storable['property']);
    }

    public function testEntitySetterMethod(): void
    {
        $entity = new EntityIntIdClass();
        $entity->setId('123');

        self::assertSame(123, $entity->id);
    }

    public function testEntityWithProtectedPropertyViaGetter(): void
    {
        $entity = EntityClass::fromArray(['id' => 1, 'prop' => 'test value']);

        self::assertSame('test value', $entity->getProp());
    }

    public function testEntityWithProtectedPropertyViaSetter(): void
    {
        $entity = new EntityClass();
        $entity->setProp('new value');

        self::assertSame('new value', $entity->getProp());
    }

    public function testEntityIssetProp(): void
    {
        $entity = new EntityClass();

        self::assertFalse($entity->issetProp());

        $entity->setProp('value');

        self::assertTrue($entity->issetProp());
    }

    public function testAsStorableArrayWithTypeCastReturnsScalarValue(): void
    {
        $entity        = EntityWithCastingsClass::fromArray(['id' => 1, 'name' => 'Test', 'score' => 100]);
        $entity->id    = 1;
        $entity->name  = 'Test';
        $entity->score = 100;

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('score', $storable);
        self::assertSame(100, $storable['score']);
    }

    public function testAsStorableArrayWithTypeContractInstanceReturnsScalarValue(): void
    {
        $entity        = EntityWithCastingsClass::fromArray(['id' => 1, 'name' => 'Test']);
        $entity->id    = 1;
        $entity->name  = 'Test';
        $entity->score = IntT::fromValue(75);

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('score', $storable);
        self::assertSame(75, $storable['score']);
    }

    public function testAsStorableArrayWithArrayCastReturnsSerializedArray(): void
    {
        $entity         = EntityWithCastingsClass::fromArray(['id' => 1, 'name' => 'Test', 'scores' => [10, 20, 30]]);
        $entity->id     = 1;
        $entity->name   = 'Test';
        $entity->scores = [10, 20, 30];

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('scores', $storable);
        self::assertIsString($storable['scores']);
        self::assertStringContainsString('10', $storable['scores']);
        self::assertStringContainsString('20', $storable['scores']);
        self::assertStringContainsString('30', $storable['scores']);
    }

    public function testAsStorableArrayWithArrayCastAndTypeContractInstances(): void
    {
        $entity         = EntityWithCastingsClass::fromArray(['id' => 1, 'name' => 'Test']);
        $entity->id     = 1;
        $entity->name   = 'Test';
        $entity->scores = [IntT::fromValue(5), IntT::fromValue(15)];

        $storable = $entity->asStorableArray();

        self::assertArrayHasKey('scores', $storable);
        self::assertIsString($storable['scores']);
        self::assertStringContainsString('5', $storable['scores']);
        self::assertStringContainsString('15', $storable['scores']);
    }

    public function testAsStorableArrayWithArrayCastThrowsExceptionForNonArrayValue(): void
    {
        $entity         = EntityWithCastingsClass::fromArray(['id' => 1, 'name' => 'Test']);
        $entity->id     = 1;
        $entity->name   = 'Test';
        $entity->scores = 'not an array';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expecting array, string provided, for scores cast');

        $entity->asStorableArray();
    }
}
