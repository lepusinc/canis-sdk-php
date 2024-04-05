<?php
declare(strict_types=1);

namespace Canis\Exception;

use Canis\Exception\Trait\Factoryable;

final class TokenInvalidException extends \RuntimeException
{
    use Factoryable;

    /**
     * Throw an exception as the token is empty.
     *
     * @param string $message
     * @return self
     */
    public static function empty(
        string $message = 'Token is empty',
    ): self
    {
        return self::factory($message);
    }

    /**
     * Throw an exception as the token is invalid.
     *
     * @param string $message
     * @return self
     */
    public static function invalid(
        string $message = 'Token is invalid',
    ): self
    {
        return self::factory($message);
    }
}
