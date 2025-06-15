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
use Valkyrja\Orm\Config\PdoConnection;

use function is_array;
use function is_int;
use function is_string;

/**
 * Class DefaultPDO.
 *
 * @author Melech Mizrachi
 */
abstract class Pdo extends BasePDO
{
    /**
     * The default options.
     *
     * @var array<int, int|bool>
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
     */
    public function __construct(
        PdoConnection $config,
        string|null $driver = null,
        string|null $dsn = null
    ) {
        $dsn = ($driver ?? 'mysql')
            . ":dbname=$config->db}"
            . $this->getDsnPart($config, 'host')
            . $this->getDsnPart($config, 'port')
            . $this->getDsnPart($config, 'user')
            . $this->getDsnPart($config, 'password')
            . ($dsn ?? '');

        $options = is_array($config->options)
            ? $config->options
            : $this->defaultOptions;

        parent::__construct(
            $dsn,
            null,
            null,
            $options
        );
    }

    /**
     * Get dsn part.
     */
    protected function getDsnPart(PdoConnection $config, string $name, string|int|null $default = null): string
    {
        $value = $config->$name ?? $default;

        return is_string($value) || is_int($value)
            ? ";$name=$value"
            : '';
    }
}
