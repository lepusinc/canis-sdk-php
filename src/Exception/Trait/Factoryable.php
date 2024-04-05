<?php
declare(strict_types=1);

namespace Canis\Exception\Trait;

use Throwable;

trait Factoryable
{
    /**
     * Factory method
     *
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     * @return self
     */
    public static function factory(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ): self
    {
        return new self($message, $code, $previous);
    }
}
