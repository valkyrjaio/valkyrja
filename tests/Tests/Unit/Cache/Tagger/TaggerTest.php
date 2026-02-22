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

namespace Valkyrja\Tests\Unit\Cache\Tagger;

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Tagger\Contract\TaggerContract;
use Valkyrja\Cache\Tagger\Tagger;
use Valkyrja\Cache\Throwable\Exception\InvalidCacheKeyException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class TaggerTest extends TestCase
{
    protected MockObject&CacheContract $cache;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheContract::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->cache->expects($this->never())->method('get');
        $this->cache->expects($this->never())->method('put');
        $tagger = new Tagger($this->cache, 'tag1');

        self::assertInstanceOf(TaggerContract::class, $tagger);
    }

    public function testMakeCreatesNewInstance(): void
    {
        $this->cache->expects($this->never())->method('get');

        $tagger = Tagger::make($this->cache, 'tag1', 'tag2');

        self::assertSame(['tag1', 'tag2'], $tagger->getTags());
    }

    /**
     * @throws JsonException
     */
    public function testHasReturnsFalseWhenKeyNotInTags(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertFalse($tagger->has('my-key'));
    }

    /**
     * @throws JsonException
     */
    public function testHasReturnsTrueWhenKeyExistsInTags(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('{"my-key":"my-key"}');

        $this->cache
            ->expects($this->once())
            ->method('has')
            ->with('my-key')
            ->willReturn(true);

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertTrue($tagger->has('my-key'));
    }

    /**
     * @throws JsonException
     */
    public function testGetThrowsWhenKeyNotInTags(): void
    {
        $key = 'my-key';

        $this->expectException(InvalidCacheKeyException::class);
        $this->expectExceptionMessage("Cache miss for key: $key");

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('{}');

        $tagger = new Tagger($this->cache, 'tag1');

        $tagger->get($key);
    }

    /**
     * @throws JsonException
     */
    public function testGetReturnsValueWhenKeyExistsInTags(): void
    {
        $count = 0;

        // First call returns the tag keys, second call returns the cached value
        $this->cache
            ->expects($this->exactly(2))
            ->method('get')
            ->with(
                self::callback(
                    static function (string $key) use (&$count): bool {
                        if ($count === 0) {
                            $count++;

                            return $key === 'tag1';
                        }

                        return $key === 'my-key';
                    }
                )
            )
            ->willReturnCallback(
                static function (string $key): string {
                    if ($key === 'tag1') {
                        return '{"my-key":"my-key"}';
                    }

                    return 'cached-value';
                }
            );

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertSame('cached-value', $tagger->get('my-key'));
    }

    /**
     * @throws JsonException
     */
    public function testManyReturnsEmptyArrayWhenNoKeysFound(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertSame([], $tagger->many('key1', 'key2'));
    }

    /**
     * @throws JsonException
     */
    public function testManyReturnsValuesWhenKeysExistInTags(): void
    {
        // Covers lines 105-111
        $this->cache
            ->expects($this->exactly(3))
            ->method('get')
            ->willReturnCallback(
                static function (string $key): string|null {
                    if ($key === 'tag1') {
                        return '{"key1":"key1","key2":"key2"}';
                    }

                    if ($key === 'key1') {
                        return 'value1';
                    }

                    return 'value2';
                }
            );

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertSame(['value1', 'value2'], $tagger->many('key1', 'key2'));
    }

    /**
     * @throws JsonException
     */
    public function testPutTagsKeyAndStoresValue(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->once())
            ->method('forever')
            ->with('tag1', self::callback(static fn (string $value): bool => str_contains($value, 'my-key')));

        $this->cache
            ->expects($this->once())
            ->method('put')
            ->with('my-key', 'my-value', 10);

        $tagger = new Tagger($this->cache, 'tag1');
        $tagger->put('my-key', 'my-value', 10);
    }

    /**
     * @throws JsonException
     */
    public function testIncrementTagsKeyAndIncrementsValue(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->once())
            ->method('forever');

        $this->cache
            ->expects($this->once())
            ->method('increment')
            ->with('my-key', 5)
            ->willReturn(10);

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertSame(10, $tagger->increment('my-key', 5));
    }

    /**
     * @throws JsonException
     */
    public function testDecrementTagsKeyAndDecrementsValue(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->once())
            ->method('forever');

        $this->cache
            ->expects($this->once())
            ->method('decrement')
            ->with('my-key', 3)
            ->willReturn(7);

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertSame(7, $tagger->decrement('my-key', 3));
    }

    /**
     * @throws JsonException
     */
    public function testForeverTagsKeyAndStoresValue(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->exactly(2))
            ->method('forever');

        $tagger = new Tagger($this->cache, 'tag1');
        $tagger->forever('my-key', 'my-value');
    }

    /**
     * @throws JsonException
     */
    public function testForgetUntagsKeyAndForgetsValue(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->once())
            ->method('forever');

        $this->cache
            ->expects($this->once())
            ->method('forget')
            ->with('my-key')
            ->willReturn(true);

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertTrue($tagger->forget('my-key'));
    }

    /**
     * @throws JsonException
     */
    public function testFlushForgetsAllTaggedKeys(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertTrue($tagger->flush());
    }

    /**
     * @throws JsonException
     */
    public function testFlushForgetsAllTaggedKeysWhenKeysExist(): void
    {
        // Covers line 207
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('{"key1":"key1","key2":"key2"}');

        $this->cache
            ->expects($this->exactly(2))
            ->method('forget')
            ->willReturn(true);

        $tagger = new Tagger($this->cache, 'tag1');

        self::assertTrue($tagger->flush());
    }

    /**
     * @throws JsonException
     */
    public function testPutManyTagsAndStoresMultipleValues(): void
    {
        // Covers lines 140-141
        $this->cache
            ->expects($this->exactly(2))
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->exactly(2))
            ->method('forever');

        $this->cache
            ->expects($this->exactly(2))
            ->method('put');

        $tagger = new Tagger($this->cache, 'tag1');
        $tagger->putMany(['key1' => 'value1', 'key2' => 'value2'], 10);
    }

    /**
     * @throws JsonException
     */
    public function testTagAddsKeyToTag(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->once())
            ->method('forever')
            ->with('tag1', self::callback(static fn (string $value): bool => str_contains($value, 'my-key')));

        $tagger = new Tagger($this->cache, 'tag1');
        $result = $tagger->tag('my-key');

        self::assertSame($tagger, $result);
    }

    /**
     * @throws JsonException
     */
    public function testUntagRemovesKeyFromTag(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->once())
            ->method('forever');

        $tagger = new Tagger($this->cache, 'tag1');
        $result = $tagger->untag('my-key');

        self::assertSame($tagger, $result);
    }

    /**
     * @throws JsonException
     */
    public function testTagManyAddsMultipleKeysToTag(): void
    {
        $this->cache
            ->expects($this->exactly(2))
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->exactly(2))
            ->method('forever');

        $tagger = new Tagger($this->cache, 'tag1');
        $result = $tagger->tagMany('key1', 'key2');

        self::assertSame($tagger, $result);
    }

    /**
     * @throws JsonException
     */
    public function testUntagManyRemovesMultipleKeysFromTag(): void
    {
        $this->cache
            ->expects($this->exactly(2))
            ->method('get')
            ->with('tag1')
            ->willReturn('');

        $this->cache
            ->expects($this->exactly(2))
            ->method('forever');

        $tagger = new Tagger($this->cache, 'tag1');
        $result = $tagger->untagMany('key1', 'key2');

        self::assertSame($tagger, $result);
    }
}
