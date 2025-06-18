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

namespace Valkyrja\Tests\Unit\Annotation;

use JsonException;
use ReflectionClass;
use Valkyrja\Annotation\Config;
use Valkyrja\Annotation\Constant\AnnotationName;
use Valkyrja\Annotation\Constant\ConfigValue;
use Valkyrja\Annotation\Constant\Regex;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Annotation\Parser\Parser;
use Valkyrja\Console\Annotation\Command;
use Valkyrja\Container\Annotation\Service;
use Valkyrja\Container\Annotation\Service\Context;
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
 * @Route("path" = "/constant", "name" = "\\Valkyrja\\Tests\\Unit\\Annotation\\ParserTest::CONSTANT")
 * @Route("path" = "/property", "name" = "\\Valkyrja\\Tests\\Unit\\Annotation\\ParserTest::property")
 */
class ParserTest extends TestCase
{
    /**
     * A constant to test with.
     *
     * @var string
     */
    public const string CONSTANT = 'constant';

    /**
     * A static property to test with.
     *
     * @var string
     */
    public static string $property = 'staticProperty';

    /**
     * A static property to test with for invalid key array (Line 257).
     */
    public static array $invalidKeyArray = ['test'];

    /**
     * The class to test with.
     *
     * @var Parser
     */
    protected Parser $parser;

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

        $this->parser = new Parser(new Config(map: ConfigValue::MAP));
    }

    /**
     * Test the getAnnotations method.
     *
     * @throws JsonException
     *
     * @return void
     */
    public function testGetAnnotations(): void
    {
        $docString = (new ReflectionClass(self::class))->getDocComment();

        $annotations = $this->parser->getAnnotations($docString);

        self::assertCount(6, $annotations);

        self::assertSame('description', $annotations[0]->getType());
        self::assertSame('author', $annotations[1]->getType());
        self::assertSame('param', $annotations[2]->getType());
        self::assertSame('Route', $annotations[3]->getType());
        self::assertSame('noAClass::Property', $annotations[3]->getName());
        self::assertSame('Route', $annotations[4]->getType());
        self::assertSame(self::CONSTANT, $annotations[4]->getName());
        self::assertSame('Route', $annotations[5]->getType());
        self::assertSame(self::$property, $annotations[5]->getName());
    }

    /**
     * Test the getArguments method.
     *
     * @throws JsonException
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
            . '"constant" = "Valkyrja\\\\Application\\\\Valkyrja::VERSION", '
            . '"property" = "Valkyrja\\\\Tests\\\\Unit\\\\Annotation\\\\ParserTest::property", '
            . '"method" = "Valkyrja\\\\Tests\\\\Unit\\\\Annotation\\\\ParserTest::staticMethod"';

        self::assertCount(7, $this->parser->getPropertiesAsArray($arguments) ?? []);
    }

    /**
     * Test the getArguments method.
     *
     * @throws JsonException
     *
     * @return void
     */
    public function testGetArgumentsNull(): void
    {
        self::assertNull($this->parser->getPropertiesAsArray());
    }

    /**
     * Test the getRegex method.
     *
     * @return void
     */
    public function testGetRegex(): void
    {
        self::assertSame(Regex::REGEX, $this->parser->getRegex());
    }

    /**
     * Test the getAnnotationsMap method.
     *
     * @return void
     */
    public function testGetAnnotationsMap(): void
    {
        self::assertSame(ConfigValue::MAP, $this->parser->getAnnotationsMap());
    }

    /**
     * Test the getAnnotationFromMap method.
     *
     * @return void
     */
    public function testGetAnnotationFromMap(): void
    {
        self::assertInstanceOf(
            Annotation::class,
            $this->parser->getAnnotationFromMap('Bogus')
        );
    }

    /**
     * Test the getAnnotationFromMap method with a Command.
     *
     * @return void
     */
    public function testGetCommandAnnotationFromMap(): void
    {
        self::assertInstanceOf(
            Command::class,
            $this->parser->getAnnotationFromMap(AnnotationName::COMMAND)
        );
    }

    /**
     * Test the getAnnotationFromMap method with a Service.
     *
     * @return void
     */
    public function testGetServiceAnnotationFromMap(): void
    {
        self::assertInstanceOf(
            Service::class,
            $this->parser->getAnnotationFromMap(AnnotationName::SERVICE)
        );
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceAlias.
     *
     * @return void
     */
    public function testGetServiceAliasAnnotationFromMap(): void
    {
        self::assertInstanceOf(
            Service\Alias::class,
            $this->parser->getAnnotationFromMap(AnnotationName::SERVICE_ALIAS)
        );
    }

    /**
     * Test the getAnnotationFromMap method with a ServiceContext.
     *
     * @return void
     */
    public function testGetServiceContextAnnotationFromMap(): void
    {
        self::assertInstanceOf(
            Context::class,
            $this->parser->getAnnotationFromMap(AnnotationName::SERVICE_CONTEXT)
        );
    }
}
