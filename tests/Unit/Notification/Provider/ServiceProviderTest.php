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

namespace Valkyrja\Tests\Unit\Notification\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Broadcast\Contract\Broadcaster;
use Valkyrja\Mail\Contract\Mailer;
use Valkyrja\Notification\Contract\Notification as Contract;
use Valkyrja\Notification\Factory\ContainerFactory;
use Valkyrja\Notification\Factory\Contract\Factory;
use Valkyrja\Notification\Notification;
use Valkyrja\Notification\Provider\ServiceProvider;
use Valkyrja\Sms\Contract\Sms;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishNotifier(): void
    {
        $this->container->setSingleton(Factory::class, $this->createStub(Factory::class));
        $this->container->setSingleton(Broadcaster::class, $this->createStub(Broadcaster::class));
        $this->container->setSingleton(Mailer::class, $this->createStub(Mailer::class));
        $this->container->setSingleton(Sms::class, $this->createStub(Sms::class));

        ServiceProvider::publishNotifier($this->container);

        self::assertInstanceOf(Notification::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishFactory(): void
    {
        ServiceProvider::publishFactory($this->container);

        self::assertInstanceOf(ContainerFactory::class, $this->container->getSingleton(Factory::class));
    }
}
