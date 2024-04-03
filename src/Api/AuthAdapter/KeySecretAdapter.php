<?php
declare(strict_types=1);

namespace Canis\Api\AuthAdapter;

use Canis\Exception\KeySecretNotFoundException;

class KeySecretAdapter implements AuthAdapterInterface
{
    /**
     * @param string $key
     * @param string $secret
     * @return $this
     */
    public function __construct(
        public string $key,
        public string $secret,
    )
    {
    }

    /**
     * @inheritDoc
     * 
     * @throws KeySecretNotFoundException
     */
    public function validate(): void
    {
        if (empty($this->key) || empty($this->secret)) {
            throw KeySecretNotFoundException::factory(
                'API client key or secret is not configured.'
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function setCredential(array $options, bool $isGet = false): array
    {
        $this->validate();

        $credentials = [
            'client_id' => $this->key,
            'client_secret' => $this->secret,
        ];

        $key = $isGet ? 'query' : 'json';

        if (empty($options[$key])) {
            $options[$key] = $credentials;
        } else {
            $options[$key] = array_merge(
                $options[$key],
                $credentials
            );
        }

        return $options;
    }
}