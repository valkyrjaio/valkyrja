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
     * Test the model's magic __get method.
     *
     * @return void
     */
    public function testMagicGet(): void
    {
        $this->assertEquals(null, $this->getModel()->property);
    }

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
     * Test the model's json serialization.
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $json                       = '{"property":"test"}';
        $this->getModel()->property = 'test';

        $this->assertEquals($json, json_encode($this->getModel()));
    }
}
