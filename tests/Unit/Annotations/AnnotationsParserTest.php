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
use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Enums\Annotation as AnnotationEnum;
use Valkyrja\Annotation\Enums\Config;
use Valkyrja\Annotation\Enums\Regex;
use Valkyrja\Annotation\Parsers\Parser;
use Valkyrja\Application\Application;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Console\Annotation\Models\Command;
use Valkyrja\Container\Annotation\Models\Alias;
use Valkyrja\Container\Annotation\Models\Context;
use Valkyrja\Container\Annotation\Models\Service;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Event\Annotation\Models\Listener;
use Valkyrja\Routing\Annotation\Models\Route;

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
    public static string $property = 'test';

    /**
     * A static property to test with for invalid key array (Line 257).
     *
     * @var string
     */
    public static $invalidKeyArray = ['test'];

    /**
     * The class to test with.
     *
     * @var Parser
     */
    protected Parser $class;

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
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Parser(new Valkyrja());
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
     * @throws ReflectionException
     * @return void
     */
    public function testGetAnnotations(): void
    {
        $docString = (new ReflectionClass(self::class))->getDocComment();

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

        $this->assertCount(7, $this->class->getPropertiesAsArray($arguments));
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArgumentsNull(): void
    {
        $this->assertEquals(null, $this->class->getPropertiesAsArray(null));
    }

    /**
     * Test the getRegex method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        $this->assertEquals(Regex::REGEX, $this->class->getRegex());
    }

    /**
     * Test the getAnnotationsMap method.
     *
     * @return void
     */
    public function testGetAnnotationsMap(): void
    {
        $this->assertEquals(Config::MAP, $this->class->getAnnotationsMap());
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
        $this->assertEquals(true, $this->class->getAnnotationFromMap(AnnotationEnum::COMMAND) instanceof Command);
    }

    /**
     * Test the getAnnotationFromMap method with a Listener.
     *
     * @return void
     */
    public function testGetListenerAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap(AnnotationEnum::LISTENER) instanceof Listener);
    }

    /**
     * Test the getAnnotationFromMap method with a Route.
     *
     * @return void
     */
    public function testGetRouteAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap(AnnotationEnum::ROUTE) instanceof Route);
    }

    /**
     * Test the getAnnotationFromMap method with a Service.
     *
     * @return void
     */
    public function testGetServiceAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap(AnnotationEnum::SERVICE) instanceof Service);
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceAlias.
     *
     * @return void
     */
    public function testGetServiceAliasAnnotationFromMap(): void
    {
        $this->assertEquals(true, $this->class->getAnnotationFromMap(AnnotationEnum::SERVICE_ALIAS) instanceof Alias);
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceContext.
     *
     * @return void
     */
    public function testGetServiceContextAnnotationFromMap(): void
    {
        $this->assertEquals(
            true, $this->class->getAnnotationFromMap(AnnotationEnum::SERVICE_CONTEXT) instanceof Context
        );
    }
}
