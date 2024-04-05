<?php
declare(strict_types=1);

namespace Canis\Api\Auth;

final class Config
{
    /**
     * Config constructor.
     *
     * @param string $endpoint
     * @param Token|null $token
     * @param string $key
     * @param string $secret
     * @param array $options
     */
    public function __construct(
        private string $endpoint = '',
        private ?Token $token = null,
        private string $key = '',
        private string $secret = '',
        private array $options = [],
    )
    {
        $token = $token ?? Token::factory();
    }

    /**
     * Factory method to create a new instance of the class.
     *
     * @param string $endpoint
     * @param Token|null $token
     * @param string $key
     * @param string $secret
     * @param array $options
     * @return self
     */
    public static function factory(
        string $endpoint = '',
        ?Token $token = null,
        string $key = '',
        string $secret = '',
        array $options = [],
    ): self
    {
        return new self(
            endpoint: $endpoint,
            token: $token ?? Token::factory(),
            key: $key,
            secret: $secret,
            options: $options
        );
    }

    /**
     * Get the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Set the key.
     *
     * @param string $key
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Get the secret.
     *
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * Set the secret.
     *
     * @param string $secret
     * @return self
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Get the token.
     *
     * @return Token
     */
    public function getToken(): Token
    {
        return $this->token;
    }

    /**
     * Set the token.
     *
     * @param Token $token
     * @return self
     */
    public function setToken(Token $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get the endpoint URL.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Set the endpoint URL.
     *
     * @param string $endpoint
     * @return self
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Get the options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the options.
     *
     * @param array $options
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Convert the class instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'token' => $this->token->toArray(),
            'key' => $this->key,
            'secret' => $this->secret,
            'options' => $this->options,
        ];
    }
}