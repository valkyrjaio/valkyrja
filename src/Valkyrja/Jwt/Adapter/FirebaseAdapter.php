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

namespace Valkyrja\Jwt\Adapter;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Jwt\Adapter\Contract\Adapter as Contract;

use function is_string;

/**
 * Abstract Class FirebaseAdapter.
 *
 * @author Melech Mizrachi
 */
abstract class FirebaseAdapter implements Contract
{
    /**
     * The algorithm.
     *
     * @var string
     */
    protected string $algorithm;

    /**
     * The decode key.
     *
     * @var OpenSSLAsymmetricKey|resource|string
     */
    protected $encodeKey;

    /**
     * The decode key.
     *
     * @var OpenSSLAsymmetricKey|resource|string
     */
    protected $decodeKey;

    /**
     * FirebaseAdapter constructor.
     *
     * @param array<string, mixed> $config The config
     */
    public function __construct(protected array $config)
    {
        $algorithm = $config['algo'];

        if (! is_string($algorithm)) {
            throw new InvalidArgumentException('Invalid algo provided');
        }

        $this->algorithm = $algorithm;

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
