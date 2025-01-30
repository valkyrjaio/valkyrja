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

namespace Valkyrja\Orm\Pdo;

use PDO as BasePDO;
use Valkyrja\Exception\InvalidArgumentException;

use function is_array;
use function is_int;
use function is_string;

/**
 * Class DefaultPDO.
 *
 * @author Melech Mizrachi
 *
 * @psalm-type Options array<int, int|bool>
 *
 * @phpstan-type Options array<int, int|bool>
 *
 * @psalm-type Config array<string, string|int|Options>
 *
 * @phpstan-type Config array<string, string|int|Options>
 */
abstract class Pdo extends BasePDO
{
    /**
     * The default options.
     *
     * @var Options
     */
    protected array $defaultOptions = [
        BasePDO::ATTR_CASE              => BasePDO::CASE_NATURAL,
        BasePDO::ATTR_ERRMODE           => BasePDO::ERRMODE_EXCEPTION,
        BasePDO::ATTR_ORACLE_NULLS      => BasePDO::NULL_NATURAL,
        BasePDO::ATTR_STRINGIFY_FETCHES => false,
        BasePDO::ATTR_EMULATE_PREPARES  => false,
    ];

    /**
     * PDO constructor.
     *
     * @param Config      $config The config
     * @param string|null $driver [optional] The driver
     * @param string|null $dsn    [optional] The added dsn
     */
    public function __construct(
        array $config,
        string|null $driver = null,
        string|null $dsn = null
    ) {
        $db = is_string($config['db'])
            ? $config['db']
            : throw new InvalidArgumentException('Invalid DB provided');

        $config['dsn'] = ($driver ?? 'mysql')
            . ":dbname=$db}"
            . $this->getDsnPart($config, 'host')
            . $this->getDsnPart($config, 'port')
            . $this->getDsnPart($config, 'user')
            . $this->getDsnPart($config, 'password')
            . ($dsn ?? '');
        $options       = is_array($config['options'])
            ? $config['options']
            : $this->defaultOptions;

        parent::__construct(
            $config['dsn'],
            null,
            null,
            $options
        );
    }

    /**
     * Get dsn part.
     *
     * @param Config          $config  The config
     * @param string          $name    The dsn part name
     * @param string|int|null $default [optional] The default value
     *
     * @return string
     */
    protected function getDsnPart(array $config, string $name, string|int|null $default = null): string
    {
        $value = $config[$name] ?? $default;

        return is_string($value) || is_int($value)
            ? ";$name=$value"
            : '';
    }
}
