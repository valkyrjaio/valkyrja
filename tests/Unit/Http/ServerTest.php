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
     * The server array to test with.
     *
     * @var array
     */
    protected $server = [
        'NON_HEADER'     => 'test',
        'BOGUS'          => 'test',
        'CONTENT_TYPE'   => 'test',
        'CONTENT_LENGTH' => 'test',
        'HTTP_HEADER'    => 'test',
    ];

    /**
     * The headers that should be returned.
     *
     * @var array
     */
    protected $headers = [
        'CONTENT_TYPE'   => 'test',
        'CONTENT_LENGTH' => 'test',
        'HTTP_HEADER'    => 'test',
    ];

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Server($this->server);
    }

    /**
     * Test the getHeaders method.
     *
     * @return void
     */
    public function testGetHeaders(): void
    {
        $this->assertEquals($this->headers, $this->class->getHeaders());
    }
}
