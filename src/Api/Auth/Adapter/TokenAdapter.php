<?php
declare(strict_types=1);

namespace Canis\Api\Auth\Adapter;

use Canis\Exception\TokenNotFoundException;

class TokenAdapter implements AuthAdapterInterface
{
    /**
     * @param string $token
     * @return $this
     */
    public function __construct(
        public string $token
    )
    {
    }
    
    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if (empty($this->token)) {
            throw new TokenNotFoundException('Token is not configured.');
        }
    }

    /**
     * @inheritDoc
     */
    public function setCredential(array $options, bool $isGet = false): array
    {
        $this->validate();

        $authorization = [
            'Authorization' => "Bearer {$this->token}",
        ];

        if (empty($options['headers'])) {
            $options['headers'] = $authorization;
        } else {
            $options['headers'] = array_merge(
                $options['headers'],
                $authorization
            );
        }

        return $options;
    }
}