<?php
declare(strict_types=1);

namespace Canis\Api\Auth;

use Carbon\Carbon;

final class Token
{
    /**
     * Token constructor.
     *
     * @param string $token
     * @param Carbon|null $expiredAt
     * @param array $options
     */
    public function __construct(
        private string $token = '',
        private ?Carbon $expiredAt = null,
        private array $options = [],
    )
    {
    }

    /**
     * Factory method to create a new instance of the class.
     *
     * @param string $token
     * @param Carbon|null $expiredAt
     * @param array $options
     * @return self
     */
    public static function factory(
        string $token = '',
        ?Carbon $expiredAt = null,
        array $options = [],
    ): self
    {
        return new self($token, $expiredAt, $options);
    }

    /**
     * Check if the token is empty.
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->token);
    }

    /**
     * Check if the token is valid.
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        return !empty($this->token) && !$this->isExpired();
    }

    /**
     * Check if the token is invalid.
     *
     * @return boolean
     */
    public function isInvalid(): bool
    {
        return !$this->isValid();
    }

    /**
     * Get the token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the token.
     *
     * @param string $token
     * @return self
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get the expiredAt.
     *
     * @return Carbon
     */
    public function getExpiredAt(): ?Carbon
    {
        return $this->expiredAt;
    }

    /**
     * Set the expiredAt.
     *
     * @param Carbon $expiredAt
     * @return self
     */
    public function setExpiredAt(?Carbon $expiredAt): self
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    /**
     * Check if the token is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiredAt && $this->expiredAt->isPast();
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
            'token' => $this->token,
            'expiredAt' => $this->expiredAt,
            'options' => $this->options,
        ];
    }
}