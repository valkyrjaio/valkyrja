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

use ReflectionClass;
use ReflectionException;
use Valkyrja\Annotation\Config;
use Valkyrja\Annotation\Constant\AnnotationName;
use Valkyrja\Annotation\Constant\ConfigValue;
use Valkyrja\Annotation\Constant\Regex;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Annotation\Parser\Parser;
use Valkyrja\Console\Annotations\Command;
use Valkyrja\Container\Annotations\Service;
use Valkyrja\Container\Annotations\Service\Context;
use Valkyrja\Event\Annotations\Listener;
use Valkyrja\Routing\Annotations\Route;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the AnnotationsParser class.
 *
 * @description A description to test with
 *
 * @author      Melech Mizrachi
 *
 * @param string $param A description test
 *
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
     * A static method to test with.
     *
     * @return string
     */
    public static function staticMethod(): string
    {
        return 'staticMethod';
    }

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new Parser((array) new Config(ConfigValue::$defaults, true));
    }

    /**
     * Test the getAnnotations method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testGetAnnotations(): void
    {
        $docString = (new ReflectionClass(self::class))->getDocComment();

        self::assertCount(4, $this->class->getAnnotations($docString));
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

        self::assertCount(7, $this->class->getPropertiesAsArray($arguments));
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArgumentsNull(): void
    {
        self::assertNull($this->class->getPropertiesAsArray(null));
    }

    /**
     * Test the getRegex method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        self::assertSame(Regex::REGEX, $this->class->getRegex());
    }

    /**
     * Test the getAnnotationsMap method.
     *
     * @return void
     */
    public function testGetAnnotationsMap(): void
    {
        self::assertSame(ConfigValue::MAP, $this->class->getAnnotationsMap());
    }

    /**
     * Test the getAnnotationFromMap method.
     *
     * @return void
     */
    public function testGetAnnotationFromMap(): void
    {
        self::assertTrue($this->class->getAnnotationFromMap('Bogus') instanceof Annotation);
    }

    /**
     * Test the getAnnotationFromMap method with a Command.
     *
     * @return void
     */
    public function testGetCommandAnnotationFromMap(): void
    {
        self::assertTrue($this->class->getAnnotationFromMap(AnnotationName::COMMAND) instanceof Command);
    }

    /**
     * Test the getAnnotationFromMap method with a Listener.
     *
     * @return void
     */
    public function testGetListenerAnnotationFromMap(): void
    {
        self::assertTrue($this->class->getAnnotationFromMap(AnnotationName::LISTENER) instanceof Listener);
    }

    /**
     * Test the getAnnotationFromMap method with a Route.
     *
     * @return void
     */
    public function testGetRouteAnnotationFromMap(): void
    {
        self::assertTrue($this->class->getAnnotationFromMap(AnnotationName::ROUTE) instanceof Route);
    }

    /**
     * Test the getAnnotationFromMap method with a Service.
     *
     * @return void
     */
    public function testGetServiceAnnotationFromMap(): void
    {
        self::assertTrue($this->class->getAnnotationFromMap(AnnotationName::SERVICE) instanceof Service);
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceAlias.
     *
     * @return void
     */
    public function testGetServiceAliasAnnotationFromMap(): void
    {
        self::assertTrue(
            $this->class->getAnnotationFromMap(AnnotationName::SERVICE_ALIAS) instanceof Service\Alias
        );
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceContext.
     *
     * @return void
     */
    public function testGetServiceContextAnnotationFromMap(): void
    {
        self::assertTrue(
            $this->class->getAnnotationFromMap(AnnotationName::SERVICE_CONTEXT) instanceof Context
        );
    }
}
