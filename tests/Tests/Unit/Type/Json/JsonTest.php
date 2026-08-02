<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Json;

use JsonException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Json\Json;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class JsonTest extends TestCase
{
    /** @var string[] */
    protected const array VALUE = ['test'];

    public function testValue(): void
    {
        $type = new Json(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $typeFromValue = Json::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testFromValueWithJson(): void
    {
        $fromJsonValue = Json::fromValue(json_encode(self::VALUE, JSON_THROW_ON_ERROR));

        self::assertSame(self::VALUE, $fromJsonValue->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $type = new Json(self::VALUE);

        self::assertSame(json_encode(self::VALUE, JSON_THROW_ON_ERROR), $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type     = new Json(self::VALUE);
        $newValue = 'fire';

        $modified = $type->modify(static function (array $subject) use ($newValue): array {
            $subject[] = $newValue;

            return $subject;
        });

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame(['test', $newValue], $modified->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $type = new Json(self::VALUE);

        self::assertSame(json_encode(self::VALUE, JSON_THROW_ON_ERROR), json_encode($type, JSON_THROW_ON_ERROR));
    }
}
