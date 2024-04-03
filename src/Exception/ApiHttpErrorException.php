<?php
declare(strict_types=1);

namespace Canis\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiHttpErrorException extends CanisException
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

    /** @var ResponseInterface $response */
    public ResponseInterface $response;

    /**
     * @param ResponseInterface $response
     * @return self
     */
    public function setHttpResponse(ResponseInterface $response): self
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getHttpResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
