<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Routing\Annotations;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Routing\Annotations\Route;

/**
 * Test the route model.
 *
 * @author Melech Mizrachi
 */
class RouteTest extends TestCase
{
    /**
     * The route model.
     *
     * @var \Valkyrja\Routing\Annotations\Route
     */
    protected $route;

    /**
     * The string value to test with.
     *
     * @var string
     */
    protected $stringValue = 'test';

    /**
     * Get the route model to test with.
     *
     * @return \Valkyrja\Routing\Annotations\Route
     */
    protected function getRoute(): Route
    {
        return $this->route ?? $this->route = new Route();
    }

    /**
     * Test the getPath getter method default value.
     *
     * @return void
     */
    public function testGetPathDefault(): void
    {
        $this->assertEquals(null, $this->getRoute()->getPath());
    }

    /**
     * Test the setPath setter method.
     *
     * @return void
     */
    public function testSetPath(): void
    {
        $set = $this->getRoute()->setPath($this->stringValue) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the getPath getter method.
     *
     * @return void
     */
    public function testGetPath(): void
    {
        $this->getRoute()->setPath($this->stringValue);

        $this->assertEquals($this->stringValue, $this->getRoute()->getPath());
    }

    /**
     * Test the getRequestMethods getter method default value.
     *
     * @return void
     */
    public function testGetRequestMethodsDefault(): void
    {
        $this->assertEquals(
            [
                RequestMethod::GET,
                RequestMethod::HEAD,
            ],
            $this->getRoute()->getRequestMethods()
        );
    }

    /**
     * Test the setRequestMethods setter method.
     *
     * @return void
     */
    public function testSetRequestMethods(): void
    {
        $set = $this->getRoute()->setRequestMethods([RequestMethod::POST]) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the setRequestMethods setter method with invalid data.
     *
     * @return void
     */
    public function testSetRequestMethodsInvalid(): void
    {
        try {
            $this->getRoute()->setRequestMethods(['invalid value']);
        } catch (Exception $exception) {
            $this->assertEquals(InvalidArgumentException::class, get_class($exception));
        }
    }

    /**
     * Test the getRequestMethods getter method.
     *
     * @return void
     */
    public function testGetRequestMethods(): void
    {
        $value = [RequestMethod::POST];
        $this->getRoute()->setRequestMethods($value);

        $this->assertEquals($value, $this->getRoute()->getRequestMethods());
    }

    /**
     * Test the getRegex getter method default value.
     *
     * @return void
     */
    public function testGetRegexDefault(): void
    {
        $this->assertEquals(null, $this->getRoute()->getRegex());
    }

    /**
     * Test the setRegex setter method.
     *
     * @return void
     */
    public function testSetRegex(): void
    {
        $set = $this->getRoute()->setRegex($this->stringValue) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the setRegex setter method using null.
     *
     * @return void
     */
    public function testSetRegexNull(): void
    {
        $set = $this->getRoute()->setRegex(null) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the getRegex getter method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        $this->getRoute()->setRegex($this->stringValue);

        $this->assertEquals($this->stringValue, $this->getRoute()->getRegex());
    }

    /**
     * Test the getParams getter method default value.
     *
     * @return void
     */
    public function testGetParamsDefault(): void
    {
        $this->assertEquals(null, $this->getRoute()->getParams());
    }

    /**
     * Test the setParams setter method.
     *
     * @return void
     */
    public function testSetParams(): void
    {
        $set = $this->getRoute()->setParams([$this->stringValue]) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the setParams setter method using null.
     *
     * @return void
     */
    public function testSetParamsNull(): void
    {
        $set = $this->getRoute()->setParams(null) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the getParams getter method.
     *
     * @return void
     */
    public function testGetParams(): void
    {
        $this->getRoute()->setParams([$this->stringValue]);

        $this->assertEquals([$this->stringValue], $this->getRoute()->getParams());
    }

    /**
     * Test the getSegments getter method default value.
     *
     * @return void
     */
    public function testGetSegmentsDefault(): void
    {
        $this->assertEquals(null, $this->getRoute()->getSegments());
    }

    /**
     * Test the setSegments setter method.
     *
     * @return void
     */
    public function testSetSegments(): void
    {
        $set = $this->getRoute()->setSegments([$this->stringValue]) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the setSegments setter method using null.
     *
     * @return void
     */
    public function testSetSegmentsNull(): void
    {
        $set = $this->getRoute()->setSegments(null) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the getSegments getter method.
     *
     * @return void
     */
    public function testGetSegments(): void
    {
        $this->getRoute()->setSegments([$this->stringValue]);

        $this->assertEquals([$this->stringValue], $this->getRoute()->getSegments());
    }

    /**
     * Test the getDynamic getter method default value.
     *
     * @return void
     */
    public function testGetDynamicDefault(): void
    {
        $this->assertEquals(false, $this->getRoute()->isDynamic());
    }

    /**
     * Test the setDynamic setter method.
     *
     * @return void
     */
    public function testSetDynamic(): void
    {
        $set = $this->getRoute()->setDynamic(true) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the getDynamic getter method.
     *
     * @return void
     */
    public function testGetDynamic(): void
    {
        $this->getRoute()->setDynamic(true);

        $this->assertEquals(true, $this->getRoute()->isDynamic());
    }

    /**
     * Test the getSecure getter method default value.
     *
     * @return void
     */
    public function testGetSecureDefault(): void
    {
        $this->assertEquals(false, $this->getRoute()->isSecure());
    }

    /**
     * Test the setSecure setter method.
     *
     * @return void
     */
    public function testSetSecure(): void
    {
        $set = $this->getRoute()->setSecure(true) ?? null;

        $this->assertEquals(null, $set);
    }

    /**
     * Test the getSecure getter method.
     *
     * @return void
     */
    public function testGetSecure(): void
    {
        $this->getRoute()->setSecure(true);

        $this->assertEquals(true, $this->getRoute()->isSecure());
    }
}