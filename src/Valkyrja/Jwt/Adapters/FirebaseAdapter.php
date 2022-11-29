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

namespace Valkyrja\Jwt\Adapters;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;
use Valkyrja\Jwt\Adapter as Contract;

/**
 * Abstract Class FirebaseAdapter.
 *
 * @author Melech Mizrachi
 */
abstract class FirebaseAdapter implements Contract
{
    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The algorithm.
     *
     * @var string
     */
    protected string $algorithm;

    /**
     * The decode key.
     *
     * @var string|resource|OpenSSLAsymmetricKey
     */
    protected $encodeKey;

    /**
     * The decode key.
     *
     * @var string|resource|OpenSSLAsymmetricKey
     */
    protected $decodeKey;

    /**
     * FirebaseAdapter constructor.
     *
     * @param array $config The config
     */
    public function __construct(array $config)
    {
        $this->config    = $config;
        $this->algorithm = $config['algo'];

        $this->setEncodeKey();
        $this->setDecodeKey();
    }

    /**
     * @inheritDoc
     */
    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->encodeKey, $this->algorithm);
    }

    /**
     * @inheritDoc
     */
    public function decode(string $jwt): array
    {
        return (array) JWT::decode($jwt, new Key($this->decodeKey, $this->algorithm));
    }

    /**
     * Get the decode key.
     *
     * @return Key
     */
    protected function getKey(): Key
    {
        return new Key($this->decodeKey, $this->algorithm);
    }

    /**
     * Set the encode key.
     *
     * @return void
     */
    abstract protected function setEncodeKey(): void;

    /**
     * Set the decode key.
     *
     * @return void
     */
    abstract protected function setDecodeKey(): void;
}
