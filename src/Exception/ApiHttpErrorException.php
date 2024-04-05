<?php
declare(strict_types=1);

namespace Canis\Exception;

use Canis\Exception\Trait\Factoryable;
use Psr\Http\Message\ResponseInterface;

final class ApiHttpErrorException extends \RuntimeException
{
    use Factoryable;
    
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
