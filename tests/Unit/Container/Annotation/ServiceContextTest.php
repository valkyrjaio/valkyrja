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

namespace Valkyrja\Tests\Unit\Container\Annotation;

use Valkyrja\Container\Annotation\Service\Context;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the service context model.
 *
 * @author Melech Mizrachi
 */
class ServiceContextTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var Context
     */
    protected Context $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected string $value = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new Context();
    }

    /**
     * Test the getService method's default value.
     *
     * @return void
     */
    public function testGetServiceDefault(): void
    {
        self::assertNull($this->class->getService());
    }

    /**
     * Test the getService method.
     *
     * @return void
     */
    public function testGetService(): void
    {
        $this->class->setService($this->value);

        self::assertSame($this->value, $this->class->getService());
    }

    /**
     * Test the setService method.
     *
     * @return void
     */
    public function testSetService(): void
    {
        self::assertSame($this->class, $this->class->setService($this->value));
    }
}
