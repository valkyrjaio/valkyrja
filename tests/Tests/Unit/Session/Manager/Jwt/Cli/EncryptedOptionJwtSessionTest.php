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
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Jwt\Cli\EncryptedOptionJwtSession;
use Valkyrja\Session\Manager\Jwt\Cli\OptionJwtSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class EncryptedOptionJwtSessionTest extends TestCase
{
    protected CryptContract&MockObject $crypt;
    protected JwtContract&MockObject $jwt;
    protected InputContract&MockObject $input;
    protected EncryptedOptionJwtSession $session;

    protected function setUp(): void
    {
        $this->crypt = $this->createMock(CryptContract::class);
        $this->jwt   = $this->createMock(JwtContract::class);
        $this->input = $this->createMock(InputContract::class);

        $this->crypt
            ->expects($this->never())
            ->method('decrypt');

        $this->jwt
            ->expects($this->never())
            ->method('decode');

        $this->input
            ->expects($this->once())
            ->method('getOption')
            ->with(OptionName::TOKEN)
            ->willReturn([]);

        $this->session = new EncryptedOptionJwtSession($this->crypt, $this->jwt, $this->input);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testExtendsOptionJwtSession(): void
    {
        self::assertInstanceOf(OptionJwtSession::class, $this->session);
    }

    public function testStartDecryptsTokenBeforeJwtDecode(): void
    {
        $crypt  = $this->createMock(CryptContract::class);
        $jwt    = $this->createMock(JwtContract::class);
        $input  = $this->createMock(InputContract::class);
        $option = $this->createMock(OptionContract::class);

        $option
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('encrypted-jwt-token');

        $input
            ->expects($this->once())
            ->method('getOption')
            ->with(OptionName::TOKEN)
            ->willReturn([$option]);

        $crypt
            ->expects($this->once())
            ->method('decrypt')
            ->with('encrypted-jwt-token')
            ->willReturn('decrypted-jwt-token');

        $jwt
            ->expects($this->once())
            ->method('decode')
            ->with('decrypted-jwt-token')
            ->willReturn(['key' => 'value', 'key2' => 'value2']);

        $session = new EncryptedOptionJwtSession($crypt, $jwt, $input);

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotDecryptWhenOptionIsEmpty(): void
    {
        $crypt = $this->createMock(CryptContract::class);
        $jwt   = $this->createMock(JwtContract::class);
        $input = $this->createMock(InputContract::class);

        $input
            ->expects($this->once())
            ->method('getOption')
            ->willReturn([]);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $session = new EncryptedOptionJwtSession($crypt, $jwt, $input);

        self::assertSame([], $session->all());
    }

    public function testStartDoesNotDecryptWhenOptionValueIsNull(): void
    {
        $crypt  = $this->createMock(CryptContract::class);
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
            ->willReturn([$option]);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $session = new EncryptedOptionJwtSession($crypt, $jwt, $input);

        self::assertSame([], $session->all());
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $crypt = $this->createMock(CryptContract::class);
        $jwt   = $this->createMock(JwtContract::class);
        $input = $this->createMock(InputContract::class);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $input
            ->expects($this->once())
            ->method('getOption')
            ->willReturn([]);

        $session = new EncryptedOptionJwtSession($crypt, $jwt, $input, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithCustomOptionName(): void
    {
        $crypt = $this->createMock(CryptContract::class);
        $jwt   = $this->createMock(JwtContract::class);
        $input = $this->createMock(InputContract::class);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $input
            ->expects($this->once())
            ->method('getOption')
            ->with('custom-token')
            ->willReturn([]);

        $session = new EncryptedOptionJwtSession($crypt, $jwt, $input, null, null, 'custom-token');

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
}
