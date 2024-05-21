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

namespace Valkyrja\Tests\Unit\Annotations;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Annotation\Annotator as Contract;
use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Annotation\Filter as FilterContract;
use Valkyrja\Annotation\Filters\Filter;
use Valkyrja\Annotation\Parser as ParserContract;
use Valkyrja\Annotation\Parsers\Parser;
use Valkyrja\Annotation\Providers\ServiceProvider;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\Tests\Unit\Container\ServiceProviderTestCase;

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
    public function testPublishAnnotator(): void
    {
        $this->container->setSingleton(Reflection::class, $this->createMock(Reflection::class));
        $this->container->setSingleton(ParserContract::class, $this->createMock(ParserContract::class));

        ServiceProvider::publishAnnotator($this->container);

        self::assertInstanceOf(Annotator::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishFilter(): void
    {
        $this->container->setSingleton(Contract::class, $this->createMock(Contract::class));

        ServiceProvider::publishFilter($this->container);

        self::assertInstanceOf(Filter::class, $this->container->getSingleton(FilterContract::class));
    }

    public function testPublishParser(): void
    {
        ServiceProvider::publishParser($this->container);

        self::assertInstanceOf(Parser::class, $this->container->getSingleton(ParserContract::class));
    }
}
