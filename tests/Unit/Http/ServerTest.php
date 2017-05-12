<?php

namespace Valkyrja\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Server;

/**
 * Test the server collection class.
 *
 * @author Melech Mizrachi
 */
class ServerTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Http\Server
     */
    protected $class;

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Server($_SERVER);
    }

    /**
     * Test the getHeaders method.
     *
     * @return void
     */
    public function testGetHeaders(): void
    {
        $this->assertEquals(true, is_array($this->class->getHeaders()));
    }
}
