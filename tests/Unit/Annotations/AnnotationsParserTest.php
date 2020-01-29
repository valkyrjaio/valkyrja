<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Annotations;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Valkyrja\Annotations\Annotation;
use Valkyrja\Annotations\NativeAnnotationsParser;
use Valkyrja\Console\Annotations\Command;
use Valkyrja\Container\Annotations\Service;
use Valkyrja\Container\Annotations\ServiceAlias;
use Valkyrja\Container\Annotations\ServiceContext;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Events\Annotations\Listener;
use Valkyrja\Routing\Annotations\Route;
use Valkyrja\Valkyrja;

/**
 * Test the AnnotationsParser class.
 *
 * @description A description to test with
 * @author      Melech Mizrachi
 *
 * @param string $param A description test
 * @Route("path" = "/", "name" = "noAClass::Property")
 */
class AnnotationsParserTest extends TestCase
{
    /**
     * A static property to test with.
     *
     * @var string
     */
    public static $property = 'test';

    /**
     * A static property to test with for invalid key array (Line 257).
     *
     * @var string
     */
    public static $invalidKeyArray = ['test'];

    /**
     * The class to test with.
     *
     * @var \Valkyrja\Annotations\NativeAnnotationsParser
     */
    protected $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected $value = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new NativeAnnotationsParser(new Valkyrja());
    }

    /**
     * A static method to test with.
     *
     * @return string
     */
    public static function staticMethod(): string
    {
        return 'staticMethod';
    }

    /**
     * Test the getAnnotations method.
     *
     * @throws \ReflectionException
     * @return void
     */
    public function testGetAnnotations(): void
    {
        $reflection = new ReflectionClass(self::class);
        $docString  = $reflection->getDocComment();

        $this->assertCount(4, $this->class->getAnnotations($docString));
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArguments(): void
    {
        $arguments = '"path" = "", '
            . '"name" = "test", '
            // Test for line 413
            . '"empty" = "", '
            . '"requestMethods" = ["POST", "GET", "HEAD"], '
            . '"constant" = "Valkyrja\\\\Application::VERSION", '
            . '"property" = "Valkyrja\\\\Tests\\\\Unit\\\\Annotations\\\\AnnotationsParserTest::property", '
            . '"method" = "Valkyrja\\\\Tests\\\\Unit\\\\Annotations\\\\AnnotationsParserTest::staticMethod"';

        $this->assertCount(7, $this->class->getArguments($arguments));
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArgumentsNull(): void
    {
        $this->assertEquals(null, $this->class->getArguments(null));
    }

    /**
     * Test the getRegex method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        $regex = '/'
            . NativeAnnotationsParser::ANNOTATION_SYMBOL
            . '([a-zA-Z]*)'
            . '(?:' . NativeAnnotationsParser::CLASS_REGEX . ')?'
            . NativeAnnotationsParser::LINE_REGEX
            . '/x';

        $this->assertEquals($regex, $this->class->getRegex());
    }

    /**
     * Test the getArgumentsRegex method.
     *
     * @return void
     */
    public function testGetArgumentsRegex(): void
    {
        $regex = '/' . NativeAnnotationsParser::ARGUMENTS_REGEX . '/x';

        $this->assertEquals($regex, $this->class->getArgumentsRegex());
    }

    /**
     * Test the getAnnotationsMap method.
     *
     * @return void
     */
    public function testGetAnnotationsMap(): void
    {
        $map = [
            'Command'        => Command::class,
            'Listener'       => Listener::class,
            'Route'          => Route::class,
            'Service'        => Service::class,
            'ServiceAlias'   => ServiceAlias::class,
            'ServiceContext' => ServiceContext::class,
        ];

        $this->assertEquals($map, $this->class->getAnnotationsMap());
    }

    /**
     * Test the getAnnotationFromMap method.
     *
     * @return void
     */
    public function testGetAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('Bogus') instanceof Annotation);
    }

    /**
     * Test the getAnnotationFromMap method with a Command.
     *
     * @return void
     */
    public function testGetCommandAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('Command') instanceof Command);
    }

    /**
     * Test the getAnnotationFromMap method with a Listener.
     *
     * @return void
     */
    public function testGetListenerAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('Listener') instanceof Listener);
    }

    /**
     * Test the getAnnotationFromMap method with a Route.
     *
     * @return void
     */
    public function testGetRouteAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('Route') instanceof Route);
    }

    /**
     * Test the getAnnotationFromMap method with a Service.
     *
     * @return void
     */
    public function testGetServiceAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('Service') instanceof Service);
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceAlias.
     *
     * @return void
     */
    public function testGetServiceAliasAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('ServiceAlias') instanceof ServiceAlias);
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceContext.
     *
     * @return void
     */
    public function testGetServiceContextAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap('ServiceContext') instanceof ServiceContext);
    }

    /**
     * Test the provides method.
     *
     * @return void
     */
    public function testProvides(): void
    {
        $expected = [
            CoreComponent::ANNOTATIONS_PARSER,
        ];

        $this->assertEquals($expected, $this->class::provides());
    }

    /**
     * Test the publish method.
     *
     * @return void
     */
    public function testPublish(): void
    {
        /* @var \Valkyrja\Application $app */
        $app = $this->createMock(\Valkyrja\Application::class);

        $this->assertEquals(null, $this->class::publish($app) ?? null);
    }
}
