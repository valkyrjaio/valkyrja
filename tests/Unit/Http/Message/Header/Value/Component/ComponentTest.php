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

namespace Valkyrja\Tests\Unit\Http\Message\Header\Value\Component;

use JsonException;
use Valkyrja\Http\Message\Header\Value\Component\Component;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class ComponentTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $component  = Component::fromValue('');
        $component2 = Component::fromValue('token');
        $component3 = Component::fromValue('token=text');

        self::assertSame('', $component->getToken());
        self::assertNull($component->getText());
        self::assertSame('', $component->__toString());
        self::assertSame('', $component->jsonSerialize());

        self::assertSame('token', $component2->getToken());
        self::assertNull($component2->getText());
        self::assertSame('token', $component2->__toString());
        self::assertSame('token', $component2->jsonSerialize());
        self::assertSame('"token"', json_encode($component2, JSON_THROW_ON_ERROR));

        self::assertSame('token', $component3->getToken());
        self::assertSame('text', $component3->getText());
        self::assertSame('token=text', $component3->__toString());
        self::assertSame('token=text', $component3->jsonSerialize());
        self::assertSame('"token=text"', json_encode($component3, JSON_THROW_ON_ERROR));
    }

    public function testToken(): void
    {
        $component  = new Component('');
        $component2 = new Component('test');
        $component3 = $component->withToken('test2');
        $component4 = $component2->withToken('test3');

        self::assertNotSame($component, $component3);
        self::assertNotSame($component2, $component4);

        self::assertSame('', $component->getToken());
        self::assertSame('test', $component2->getToken());
        self::assertSame('test2', $component3->getToken());
        self::assertSame('test3', $component4->getToken());
    }

    public function testText(): void
    {
        $component  = new Component('', 'test');
        $component2 = new Component('token', 'test2');
        $component3 = $component->withText('test3');
        $component4 = $component2->withText('test4');

        self::assertNotSame($component, $component3);
        self::assertNotSame($component2, $component4);

        self::assertSame('test', $component->getText());
        self::assertSame('test2', $component2->getText());
        self::assertSame('test3', $component3->getText());
        self::assertSame('test4', $component4->getText());

        self::assertSame('', $component->__toString());
        self::assertSame('token=test2', $component2->__toString());
        self::assertSame('', $component3->__toString());
        self::assertSame('token=test4', $component4->__toString());
    }
}
