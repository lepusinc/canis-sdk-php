<?php
declare(strict_types=1);

namespace Canis\Api\Auth\Adapter;

use Canis\Api\Auth\Token;
use Canis\Exception\TokenInvalidException;

class TokenAdapter implements AuthAdapterInterface
{
    /**
     * @param Token $token
     * @return $this
     */
    public function __construct(
        public Token $token
    )
    {
    }
    
    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->token->isEmpty()) {
            throw TokenInvalidException::empty(
                'This adapter requires token but not found.'
            );
        }

        if ($this->token->isInvalid()) {
            throw TokenInvalidException::invalid(
                'This adapter requires valid token but it is invalid.'
            );
        }

    }

    /**
     * @inheritDoc
     */
    public function setCredential(array $options, bool $isGet = false): array
    {
        $this->validate();

        $authorization = [
            'Authorization' => "Bearer " . $this->token->getToken(),
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