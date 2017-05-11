<?php

namespace Valkyrja\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;

/**
 * Test the abstract model.
 *
 * @author Melech Mizrachi
 */
class ModelTest extends TestCase
{
    /**
     * The model class.
     *
     * @var \Valkyrja\Tests\Unit\Model\ModelClass
     */
    protected $model;

    /**
     * Get the model class to test with.
     *
     * @return \Valkyrja\Tests\Unit\Model\ModelClass
     */
    protected function getModel(): ModelClass
    {
        return $this->model ?? $this->model = new ModelClass();
    }

    /**
     * Test the model's magic __get method.
     *
     * @return void
     */
    public function testMagicGet(): void
    {
        $this->assertEquals(null, $this->getModel()->property);
    }

    /**
     * Test the model's getter through the magic __get method.
     *
     * @return void
     */
    public function testMagicGetter(): void
    {
        $this->assertEquals(null, $this->getModel()->prop);
    }

    /**
     * Test the model's magic __set method.
     *
     * @return void
     */
    public function testMagicSet(): void
    {
        $value                      = 'test';
        $this->getModel()->property = $value;

        $this->assertEquals($value, $this->getModel()->property);
    }

    /**
     * Test the model's setter through the magic __set method.
     *
     * @return void
     */
    public function testMagicSetter(): void
    {
        $value                  = 'test';
        $this->getModel()->prop = $value;

        $this->assertEquals($value, $this->getModel()->prop);
    }

    /**
     * Test the model's magic isset method.
     *
     * @return void
     */
    public function testMagicIsset(): void
    {
        $this->getModel()->property = 'test';

        $this->assertEquals(true, isset($this->getModel()->property));
    }

    /**
     * Test the model's isset through magic isset method.
     *
     * @return void
     */
    public function testMagicIssetMethod(): void
    {
        $this->getModel()->prop = 'test';

        $this->assertEquals(true, isset($this->getModel()->prop));
    }

    /**
     * Test the model's json serialization.
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $json = json_encode([
            'property' => null,
            'prop'     => null,
        ]);

        $this->assertEquals($json, json_encode($this->getModel()));
    }
}
