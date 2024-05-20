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

namespace Valkyrja\Tests\Unit\Dispatcher;

use Valkyrja\Dispatcher\Dispatch as Contract;
use Valkyrja\Dispatcher\Models\Dispatch;
use Valkyrja\Model\Model;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the dispatch model.
 *
 * @author Melech Mizrachi
 */
class DispatchTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Dispatcher\Dispatch
     */
    protected Dispatch $dispatch;

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

        $this->dispatch = new Dispatch();
    }

    public function testContract(): void
    {
        self::assertMethodExists(Contract::class, 'getId');
        self::assertMethodExists(Contract::class, 'setId');
        self::assertMethodExists(Contract::class, 'getName');
        self::assertMethodExists(Contract::class, 'setName');
        self::assertMethodExists(Contract::class, 'getClass');
        self::assertMethodExists(Contract::class, 'setClass');
        self::assertMethodExists(Contract::class, 'isClass');
        self::assertMethodExists(Contract::class, 'getProperty');
        self::assertMethodExists(Contract::class, 'setProperty');
        self::assertMethodExists(Contract::class, 'isProperty');
        self::assertMethodExists(Contract::class, 'getMethod');
        self::assertMethodExists(Contract::class, 'setMethod');
        self::assertMethodExists(Contract::class, 'isMethod');
        self::assertMethodExists(Contract::class, 'isStatic');
        self::assertMethodExists(Contract::class, 'setStatic');
        self::assertMethodExists(Contract::class, 'getFunction');
        self::assertMethodExists(Contract::class, 'setFunction');
        self::assertMethodExists(Contract::class, 'isFunction');
        self::assertMethodExists(Contract::class, 'getClosure');
        self::assertMethodExists(Contract::class, 'setClosure');
        self::assertMethodExists(Contract::class, 'isClosure');
        self::assertMethodExists(Contract::class, 'getConstant');
        self::assertMethodExists(Contract::class, 'setConstant');
        self::assertMethodExists(Contract::class, 'isConstant');
        self::assertMethodExists(Contract::class, 'getVariable');
        self::assertMethodExists(Contract::class, 'setVariable');
        self::assertMethodExists(Contract::class, 'isVariable');
        self::assertMethodExists(Contract::class, 'getMatches');
        self::assertMethodExists(Contract::class, 'setMatches');
        self::assertMethodExists(Contract::class, 'getArguments');
        self::assertMethodExists(Contract::class, 'setArguments');
        self::assertMethodExists(Contract::class, 'getDependencies');
        self::assertMethodExists(Contract::class, 'setDependencies');
        self::assertIsA(Model::class, Contract::class);
    }

    /**
     * Test the getId method's default value.
     *
     * @return void
     */
    public function testGetIdDefault(): void
    {
        self::assertNull($this->dispatch->getId());
    }

    /**
     * Test the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        $this->dispatch->setId($this->value);

        self::assertSame($this->value, $this->dispatch->getId());
    }

    /**
     * Test the setId method with null value.
     *
     * @return void
     */
    public function testSetIdNull(): void
    {
        $set = $this->dispatch->setId(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setId method.
     *
     * @return void
     */
    public function testSetId(): void
    {
        $set = $this->dispatch->setId($this->value);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the getName method's default value.
     *
     * @return void
     */
    public function testGetNameDefault(): void
    {
        self::assertNull($this->dispatch->getName());
    }

    /**
     * Test the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->dispatch->setName($this->value);

        self::assertSame($this->value, $this->dispatch->getName());
    }

    /**
     * Test the setName method with null value.
     *
     * @return void
     */
    public function testSetNameNull(): void
    {
        $set = $this->dispatch->setName(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setName method.
     *
     * @return void
     */
    public function testSetName(): void
    {
        $set = $this->dispatch->setName($this->value);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the getClass method's default value.
     *
     * @return void
     */
    public function testGetClassDefault(): void
    {
        self::assertNull($this->dispatch->getClass());
    }

    /**
     * Test the getClass method.
     *
     * @return void
     */
    public function testGetClass(): void
    {
        $this->dispatch->setClass($this->value);

        self::assertSame($this->value, $this->dispatch->getClass());
    }

    /**
     * Test the setClass method with null value.
     *
     * @return void
     */
    public function testSetClassNull(): void
    {
        $set = $this->dispatch->setClass(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setClass method.
     *
     * @return void
     */
    public function testSetClass(): void
    {
        $set = $this->dispatch->setClass($this->value);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the getProperty method's default value.
     *
     * @return void
     */
    public function testGetPropertyDefault(): void
    {
        self::assertNull($this->dispatch->getProperty());
    }

    /**
     * Test the getProperty method.
     *
     * @return void
     */
    public function testGetProperty(): void
    {
        $this->dispatch->setProperty($this->value);

        self::assertSame($this->value, $this->dispatch->getProperty());
    }

    /**
     * Test the setProperty method with null value.
     *
     * @return void
     */
    public function testSetPropertyNull(): void
    {
        $set = $this->dispatch->setProperty(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setProperty method.
     *
     * @return void
     */
    public function testSetProperty(): void
    {
        $set = $this->dispatch->setProperty($this->value);

        self::assertTrue($set instanceof Dispatch);
        self::assertSame($this->value, $this->dispatch->getProperty());
        self::assertTrue($this->dispatch->isProperty());
    }

    /**
     * Test the getMethod method's default value.
     *
     * @return void
     */
    public function testGetMethodDefault(): void
    {
        self::assertNull($this->dispatch->getMethod());
    }

    /**
     * Test the getMethod method.
     *
     * @return void
     */
    public function testGetMethod(): void
    {
        $this->dispatch->setMethod($this->value);

        self::assertSame($this->value, $this->dispatch->getMethod());
    }

    /**
     * Test the setMethod method with null value.
     *
     * @return void
     */
    public function testSetMethodNull(): void
    {
        $set = $this->dispatch->setMethod(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setMethod method.
     *
     * @return void
     */
    public function testSetMethod(): void
    {
        $set = $this->dispatch->setMethod($this->value);

        self::assertTrue($set instanceof Dispatch);
        self::assertSame($this->value, $this->dispatch->getMethod());
        self::assertTrue($this->dispatch->isMethod());
    }

    /**
     * Test the isStatic method.
     *
     * @return void
     */
    public function testIsStatic(): void
    {
        $this->dispatch->setStatic(true);

        self::assertTrue($this->dispatch->isStatic());
    }

    /**
     * Test the setStatic method.
     *
     * @return void
     */
    public function testSetStatic(): void
    {
        $set = $this->dispatch->setStatic(true);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the getFunction method's default value.
     *
     * @return void
     */
    public function testGetFunctionDefault(): void
    {
        self::assertNull($this->dispatch->getFunction());
    }

    /**
     * Test the getFunction method.
     *
     * @return void
     */
    public function testGetFunction(): void
    {
        $this->dispatch->setFunction($this->value);

        self::assertSame($this->value, $this->dispatch->getFunction());
    }

    /**
     * Test the setFunction method with null value.
     *
     * @return void
     */
    public function testSetFunctionNull(): void
    {
        $set = $this->dispatch->setFunction(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setFunction method.
     *
     * @return void
     */
    public function testSetFunction(): void
    {
        $set = $this->dispatch->setFunction($this->value);

        self::assertTrue($set instanceof Dispatch);
        self::assertSame($this->value, $this->dispatch->getFunction());
        self::assertTrue($this->dispatch->isFunction());
    }

    /**
     * Test the getClosure method's default value.
     *
     * @return void
     */
    public function testGetClosureDefault(): void
    {
        self::assertNull($this->dispatch->getClosure());
    }

    /**
     * Test the getClosure method.
     *
     * @return void
     */
    public function testGetClosure(): void
    {
        $value = static function (): void {
        };
        $this->dispatch->setClosure($value);

        self::assertSame($value, $this->dispatch->getClosure());
    }

    /**
     * Test the setClosure method with null value.
     *
     * @return void
     */
    public function testSetClosureNull(): void
    {
        $set = $this->dispatch->setClosure(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setClosure method.
     *
     * @return void
     */
    public function testSetClosure(): void
    {
        $set = $this->dispatch->setClosure(
            $value = static function (): void {
            }
        );

        self::assertTrue($set instanceof Dispatch);
        self::assertSame($value, $this->dispatch->getClosure());
        self::assertTrue($this->dispatch->isClosure());
    }

    /**
     * Test the getConstant constant's default value.
     *
     * @return void
     */
    public function testGetConstantDefault(): void
    {
        self::assertNull($this->dispatch->getConstant());
    }

    /**
     * Test the getConstant constant.
     *
     * @return void
     */
    public function testGetConstant(): void
    {
        $this->dispatch->setConstant($this->value);

        self::assertSame($this->value, $this->dispatch->getConstant());
    }

    /**
     * Test the setConstant constant with null value.
     *
     * @return void
     */
    public function testSetConstantNull(): void
    {
        $set = $this->dispatch->setConstant(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setConstant constant.
     *
     * @return void
     */
    public function testSetConstant(): void
    {
        $set = $this->dispatch->setConstant($this->value);

        self::assertTrue($set instanceof Dispatch);
        self::assertSame($this->value, $this->dispatch->getConstant());
        self::assertTrue($this->dispatch->isConstant());
    }

    /**
     * Test the getVariable variable's default value.
     *
     * @return void
     */
    public function testGetVariableDefault(): void
    {
        self::assertNull($this->dispatch->getVariable());
    }

    /**
     * Test the getVariable variable.
     *
     * @return void
     */
    public function testGetVariable(): void
    {
        $this->dispatch->setVariable($this->value);

        self::assertSame($this->value, $this->dispatch->getVariable());
    }

    /**
     * Test the setVariable variable with null value.
     *
     * @return void
     */
    public function testSetVariableNull(): void
    {
        $set = $this->dispatch->setVariable(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setVariable variable.
     *
     * @return void
     */
    public function testSetVariable(): void
    {
        $set = $this->dispatch->setVariable($this->value);

        self::assertTrue($set instanceof Dispatch);
        self::assertSame($this->value, $this->dispatch->getVariable());
        self::assertTrue($this->dispatch->isVariable());
    }

    /**
     * Test the getMatches method's default value.
     *
     * @return void
     */
    public function testGetMatchesDefault(): void
    {
        self::assertNull($this->dispatch->getMatches());
    }

    /**
     * Test the getMatches method.
     *
     * @return void
     */
    public function testGetMatches(): void
    {
        $this->dispatch->setMatches([$this->value]);

        self::assertSame([$this->value], $this->dispatch->getMatches());
    }

    /**
     * Test the setMatches method with null value.
     *
     * @return void
     */
    public function testSetMatchesNull(): void
    {
        $set = $this->dispatch->setMatches(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setMatches method.
     *
     * @return void
     */
    public function testSetMatches(): void
    {
        $set = $this->dispatch->setMatches([$this->value]);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the getDependencies method's default value.
     *
     * @return void
     */
    public function testGetDependenciesDefault(): void
    {
        self::assertNull($this->dispatch->getDependencies());
    }

    /**
     * Test the getDependencies method.
     *
     * @return void
     */
    public function testGetDependencies(): void
    {
        $this->dispatch->setDependencies([$this->value]);

        self::assertSame([$this->value], $this->dispatch->getDependencies());
    }

    /**
     * Test the setDependencies method with null value.
     *
     * @return void
     */
    public function testSetDependenciesNull(): void
    {
        $set = $this->dispatch->setDependencies(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setDependencies method.
     *
     * @return void
     */
    public function testSetDependencies(): void
    {
        $set = $this->dispatch->setDependencies([$this->value]);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the getArguments method's default value.
     *
     * @return void
     */
    public function testGetArgumentsDefault(): void
    {
        self::assertNull($this->dispatch->getArguments());
    }

    /**
     * Test the getArguments method.
     *
     * @return void
     */
    public function testGetArguments(): void
    {
        $this->dispatch->setArguments([$this->value]);

        self::assertSame([$this->value], $this->dispatch->getArguments());
    }

    /**
     * Test the setArguments method with null value.
     *
     * @return void
     */
    public function testSetArgumentsNull(): void
    {
        $set = $this->dispatch->setArguments(null);

        self::assertTrue($set instanceof Dispatch);
    }

    /**
     * Test the setArguments method.
     *
     * @return void
     */
    public function testSetArguments(): void
    {
        $set = $this->dispatch->setArguments([$this->value]);

        self::assertTrue($set instanceof Dispatch);
    }
}
