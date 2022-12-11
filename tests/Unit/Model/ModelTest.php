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

namespace Valkyrja\Tests\Unit\Model;

use JsonException;
use PHPUnit\Framework\TestCase;
use Valkyrja\Tests\Classes\Model\Model;

use function json_encode;

use const JSON_THROW_ON_ERROR;

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
     * @var Model
     */
    protected Model $model;

    /**
     * Get the model class to test with.
     *
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->model ?? $this->model = new Model();
    }

    /**
     * Test the model's magic __get method.
     *
     * @return void
     */
    public function testMagicGet(): void
    {
        self::assertEquals(null, $this->getModel()->property);
    }

    /**
     * Test the model's getter through the magic __get method.
     *
     * @return void
     */
    public function testMagicGetter(): void
    {
        self::assertEquals(null, $this->getModel()->prop);
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

        self::assertEquals($value, $this->getModel()->property);
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

        self::assertEquals($value, $this->getModel()->prop);
    }

    /**
     * Test the model's magic isset method.
     *
     * @return void
     */
    public function testMagicIsset(): void
    {
        $this->getModel()->property = 'test';

        self::assertEquals(true, isset($this->getModel()->property));
    }

    /**
     * Test the model's isset through magic isset method.
     *
     * @return void
     */
    public function testMagicIssetMethod(): void
    {
        $this->getModel()->prop = 'test';

        self::assertEquals(true, isset($this->getModel()->prop));
    }

    /**
     * Test the model's json serialization.
     *
     * @throws JsonException
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $json = json_encode(
            [
                'property' => null,
                'prop'     => null,
            ],
            JSON_THROW_ON_ERROR
        );

        self::assertEquals($json, json_encode($this->getModel(), JSON_THROW_ON_ERROR));
    }
}
