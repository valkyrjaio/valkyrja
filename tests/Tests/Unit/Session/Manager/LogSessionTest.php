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

namespace Valkyrja\Tests\Unit\Session\Manager;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\LogSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LogSessionTest extends TestCase
{
    protected LoggerContract&MockObject $logger;

    protected LogSession $session;

    protected function setUp(): void
    {
        $this->logger  = $this->createMock(LoggerContract::class);
        $this->session = new LogSession($this->logger);
    }

    public function testImplementsSessionContract(): void
    {
        $this->logger
            ->expects($this->never())
            ->method('info');

        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testStartDoesNothing(): void
    {
        $this->logger
            ->expects($this->never())
            ->method('info');

        // Start is a no-op
        $this->session->start();

        self::assertSame([], $this->session->all());
    }

    public function testSetLogsSessionData(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with("_session\n{\"key\":\"value\"}");

        $this->session->set('key', 'value');

        self::assertSame('value', $this->session->get('key'));
    }

    public function testRemoveLogsSessionDataWhenItemExists(): void
    {
        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        $this->session->set('key', 'value');
        $result = $this->session->remove('key');

        self::assertTrue($result);
        self::assertFalse($this->session->has('key'));
    }

    public function testRemoveDoesNotLogWhenItemDoesNotExist(): void
    {
        $this->logger
            ->expects($this->never())
            ->method('info');

        $result = $this->session->remove('nonexistent');

        self::assertFalse($result);
    }

    public function testClearLogsSessionData(): void
    {
        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        $this->session->set('key', 'value');
        $this->session->clear();

        self::assertSame([], $this->session->all());
    }

    public function testDestroyLogsSessionData(): void
    {
        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        $this->session->set('key', 'value');
        $this->session->destroy();

        self::assertSame([], $this->session->all());
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $this->logger
            ->expects($this->never())
            ->method('info');

        $session = new LogSession($this->logger, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testLogFormatIncludesSessionIdAndData(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with("my-session_session\n{\"key\":\"value\"}");

        $session = new LogSession($this->logger, 'my-session');
        $session->set('key', 'value');
    }
}
