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

namespace Valkyrja\Tests\Unit\Session\Manager\Jwt\Cli;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Jwt\Cli\OptionJwtSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class OptionJwtSessionTest extends TestCase
{
    protected JwtContract&MockObject $jwt;
    protected InputContract&MockObject $input;
    protected OptionJwtSession $session;

    protected function setUp(): void
    {
        $this->jwt   = $this->createMock(JwtContract::class);
        $this->input = $this->createMock(InputContract::class);

        $this->jwt
            ->expects($this->never())
            ->method('decode');

        $this->input
            ->expects($this->once())
            ->method('getOption')
            ->with(OptionName::TOKEN)
            ->willReturn([]);

        $this->session = new OptionJwtSession($this->jwt, $this->input);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testStartDecodesJwtToken(): void
    {
        $jwt    = $this->createMock(JwtContract::class);
        $input  = $this->createMock(InputContract::class);
        $option = $this->createMock(OptionContract::class);

        $option
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.test');

        $input
            ->expects($this->once())
            ->method('getOption')
            ->with(OptionName::TOKEN)
            ->willReturn([$option]);

        $jwt
            ->expects($this->once())
            ->method('decode')
            ->with('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.test')
            ->willReturn(['key' => 'value', 'key2' => 'value2']);

        $session = new OptionJwtSession($jwt, $input);

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotDecodeWhenOptionIsEmpty(): void
    {
        $jwt   = $this->createMock(JwtContract::class);
        $input = $this->createMock(InputContract::class);

        $input
            ->expects($this->once())
            ->method('getOption')
            ->with(OptionName::TOKEN)
            ->willReturn([]);

        $jwt
            ->expects($this->never())
            ->method('decode');

        $session = new OptionJwtSession($jwt, $input);

        self::assertSame([], $session->all());
    }

    public function testStartDoesNotDecodeWhenOptionValueIsNull(): void
    {
        $jwt    = $this->createMock(JwtContract::class);
        $input  = $this->createMock(InputContract::class);
        $option = $this->createMock(OptionContract::class);

        $option
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(null);

        $input
            ->expects($this->once())
            ->method('getOption')
            ->with(OptionName::TOKEN)
            ->willReturn([$option]);

        $jwt
            ->expects($this->never())
            ->method('decode');

        $session = new OptionJwtSession($jwt, $input);

        self::assertSame([], $session->all());
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $jwt   = $this->createMock(JwtContract::class);
        $input = $this->createMock(InputContract::class);

        $jwt
            ->expects($this->never())
            ->method('decode');

        $input
            ->expects($this->once())
            ->method('getOption')
            ->willReturn([]);

        $session = new OptionJwtSession($jwt, $input, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithCustomOptionName(): void
    {
        $jwt   = $this->createMock(JwtContract::class);
        $input = $this->createMock(InputContract::class);

        $jwt
            ->expects($this->never())
            ->method('decode');

        $input
            ->expects($this->once())
            ->method('getOption')
            ->with('custom-token')
            ->willReturn([]);

        $session = new OptionJwtSession($jwt, $input, null, null, 'custom-token');

        self::assertSame('', $session->getId());
    }

    public function testSetStoresValue(): void
    {
        $this->session->set('key', 'value');

        self::assertSame('value', $this->session->get('key'));
    }

    public function testGetReturnsDefaultForNonExistent(): void
    {
        self::assertSame('default', $this->session->get('nonexistent', 'default'));
    }

    public function testHasReturnsTrueForExistingItem(): void
    {
        $this->session->set('key', 'value');

        self::assertTrue($this->session->has('key'));
    }

    public function testHasReturnsFalseForNonExistentItem(): void
    {
        self::assertFalse($this->session->has('nonexistent'));
    }

    public function testRemoveReturnsTrueWhenItemExists(): void
    {
        $this->session->set('key', 'value');

        self::assertTrue($this->session->remove('key'));
        self::assertFalse($this->session->has('key'));
    }

    public function testRemoveReturnsFalseWhenItemDoesNotExist(): void
    {
        self::assertFalse($this->session->remove('nonexistent'));
    }
}