<?php
declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Http\Exceptions;

use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Exceptions\HttpRedirectException;

/**
 * Test the HttpRedirectException class.
 *
 * @author Melech Mizrachi
 */
class HttpRedirectExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var HttpRedirectException
     */
    protected HttpRedirectException $exception;

    /**
     * Get the exception.
     *
     * @return HttpRedirectException
     */
    protected function getException(): HttpRedirectException
    {
        return $this->exception ?? $this->exception = new HttpRedirectException();
    }

    /**
     * Test the construction of a new HttpRedirectException instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertEquals(true, $this->getException() instanceof HttpRedirectException);
    }

    /**
     * Test the getUri method.
     *
     * @return void
     */
    public function testGetUri(): void
    {
        $this->assertEquals('/', $this->getException()->getUri());
    }
}
