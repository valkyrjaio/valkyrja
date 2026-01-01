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
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Notification\Factory\ContainerFactory;
use Valkyrja\Notification\Factory\Contract\FactoryContract;
use Valkyrja\Notification\Notifier\Contract\NotifierContract as Contract;
use Valkyrja\Notification\Notifier\Notifier;
use Valkyrja\Notification\Provider\ServiceProvider;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
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
        $this->container->setSingleton(FactoryContract::class, self::createStub(FactoryContract::class));
        $this->container->setSingleton(BroadcasterContract::class, self::createStub(BroadcasterContract::class));
        $this->container->setSingleton(MailerContract::class, self::createStub(MailerContract::class));
        $this->container->setSingleton(MessengerContract::class, self::createStub(MessengerContract::class));

        ServiceProvider::publishNotifier($this->container);

        self::assertInstanceOf(Notifier::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishFactory(): void
    {
        ServiceProvider::publishFactory($this->container);

        self::assertInstanceOf(ContainerFactory::class, $this->container->getSingleton(FactoryContract::class));
    }
}
