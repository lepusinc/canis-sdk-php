<?php
declare(strict_types=1);

namespace Canis\Api;

use Canis\Api\Auth\Adapter\AuthAdapterInterface;
use Canis\Api\Auth\Adapter\KeySecretAdapter;
use Canis\Api\Auth\Adapter\TokenAdapter;
use Canis\Api\Auth\Config;
use Canis\Exception\ApiHttpErrorException;
use Canis\Exception\ApiUrlNotFoundException;
use Canis\Exception\TokenInvalidException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class ApiAbstract
{
    const API_VERSION = 'v1';

    /**
     * @var \GuzzleHttp\Client $httpClient
     */
    public $httpClient;

    /**
     * @var AuthAdapterInterface $authAdapter
     */
    public $authAdapter;

    /**
     * @var array<string,string> $placeholders
     * @see replacePlaceholders
     */
    public array $placeholders = [];

    /**
     * @var LoggerInterface|null $logger
     */
    public ?LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
    
    /**
     * @param Config $config
     * @return $this
     */
    public function __construct(
        public Config $config
    )
    {
        $this->init();
    }

    /**
     * Initialize the API client with the configuration.
     * 
     * @return self
     */
    public function init(): self
    {
        $this->httpClient = $this->getHttpClient();

        return $this;
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        if (!$this->httpClient instanceof \GuzzleHttp\Client) {
            $this->httpClient = new \GuzzleHttp\Client();
        }

        return $this->httpClient;
    }

    /**
     * Get a instance of the AuthAdapterInterface.
     *
     * @return AuthAdapterInterface
     */
    public function getAdapter(): AuthAdapterInterface
    {
        return $this->authAdapter;
    }

    /**
     * @param array<string,string> $placeholders
     * @return self
     */
    public function setPlaceholders(array $placeholders): self
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * Send GET request.
     * 
     * @param string $uri
     * @param array<string,string> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws \Exception
     */
    public function get(string $uri, array $query = [], array $options = []): array
    {
        $options = array_merge(
            $options,
            [
                'query' => $query,
            ]
        );

        return $this->sendRequest('GET', $uri, $options);
    }

    /**
     * Send POST request.
     * 
     * @param string $uri
     * @param array<string,string> $formParams
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws \Exception
     */
    public function post(string $uri, array $formParams = [], array $options = []): array
    {
        $options = array_merge(
            $options,
            [
                'json' => $formParams,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        return $this->sendRequest('POST', $uri, $options);
    }

    /**
     * Send PUT request.
     * 
     * @param string $uri
     * @param array<string,string> $formParams
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws \Exception
     */
    public function put(string $uri, array $formParams = [], array $options = []): array
    {
        $options = array_merge(
            $options,
            [
                'json' => $formParams,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        return $this->sendRequest('PUT', $uri, $options);
    }

    /**
     * Send DELETE request.
     * 
     * @param string $uri
     * @param array<string,string> $formParams
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws \Exception
     */
    public function delete(string $uri, array $formParams = [], array $options = []): array
    {
        $options = array_merge(
            $options,
            [
                'json' => $formParams,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        return $this->sendRequest('DELETE', $uri, $options);
    }

    /**
     * Send request
     * @param string $method
     * @param string $uri
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     * @throws \Exception
     */
    public function sendRequest(string $method, string $uri, array $options = []): array
    {
        $this->validate();

        $endpoint = rtrim($this->config->getEndpoint(), '/');

        /**
         * Handle request options and set credentials if 
         * adapter is available
         */
        $options = array_merge($this->defaultHttpOptions(), $options);
        $options = $this->setCredential($options, $method);

        /**
         * @see replacePlaceholders
         */
        if (str_contains($uri, ':')) {
            $uri = $this->replacePlaceholders($uri);
        }
        
        /**
         * Create and send an HTTP request
         */
        $endpoint = $endpoint . '/' . self::API_VERSION . $uri;
        try {

            $this->log('debug', "{$method} {$endpoint}");

            $response = $this->getHttpClient()->request(
                $method,
                $endpoint,
                $options
            );

        } catch (ClientException $e) {
            // For 4xx status codes
            if ($e->getResponse()->getStatusCode() !== 200) {
                $this->logHttp('warn', $method, $endpoint, $options, $e->getResponse());

                throw ApiHttpErrorException::factory($e->getResponse()->getReasonPhrase())
                    ->setHttpResponse($e->getResponse())
                ;
            }

            throw $e;
        } catch (ServerException $e) {
            // For 5xx status codes
            if ($e->getResponse()->getStatusCode() !== 200) {
                $this->logHttp('warn', $method, $endpoint, $options, $e->getResponse());

                throw ApiHttpErrorException::factory($e->getResponse()->getReasonPhrase())
                    ->setHttpResponse($e->getResponse())
                ;
            }

            throw $e;
        }
        $results = @json_decode((string) $response->getBody(), true);

        $this->logHttp('debug', $method, $endpoint, $options, $response);

        return empty($results) || !is_array($results) ? [] : $results;
    }

    /**
     * Log http requests and responses
     *
     * @param string $level
     * @param string $method
     * @param string $endpoint
     * @param array<string,string> $options
     * @param ResponseInterface|null $response
     * @return void
     */
    protected function logHttp(string $level, string $method, string $endpoint, array $options, ?ResponseInterface $response = null): void
    {
        $status_code = !empty($response) ? $response->getStatusCode() : '200';
        $message = !empty($response) ? $response->getReasonPhrase() : '';
        $optionsJson = json_encode($options);
        $this->log($level, "Request: {$method} {$endpoint} {$status_code} {$message}");
        $this->log($level, "Request options: {$optionsJson}");

        if (!empty($response)) {
            $body = $response->getBody();
            $this->log($level, "Response: {$body}");
        }
    }

    /**
     * Log message
     *
     * @param string $level
     * @param string $message
     * @return void
     * @see \Monolog\Logger
     */
    protected function log(string $level, string $message): void
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger::{$level}($message);
    }

    /**
     * Initialize adapter by using KeySecret adapter
     *
     * @return self
     */
    public function withKeySecretAdapter(): self
    {
        $this->authAdapter = new KeySecretAdapter(
            $this->config->getKey(),
            $this->config->getSecret(),
        );
        return $this;
    }

    /**
     * Initialize adapter by using token adapter
     *
     * @return self
     */
    public function withTokenAdapter(): self
    {
        if ($this->config->getToken()->isEmpty()) {
            throw TokenInvalidException::empty(
                'This adapter requires token but not found.'
            );
        }

        if ($this->config->getToken()->isInvalid()) {
            throw TokenInvalidException::invalid(
                'This adapter requires valid token but it is invalid.'
            );
        }

        $this->authAdapter = new TokenAdapter(
            $this->config->getToken(),
        );
        return $this;
    }

    /**
     * Return default options for GuzzleHttp client
     *
     * @return array<string,mixed>
     */
    protected function defaultHttpOptions(): array
    {
        return [
            
        ];
    }

    /**
     * Set credential into request options if needed
     *
     * @param array<string,string> $options
     * @param string $method
     * @return array<string,string>
     */
    protected function setCredential(array $options, string $method): array
    {
        if ($this->authAdapter instanceof AuthAdapterInterface) {
            $is_get = ($method === 'GET');
            $options = $this->authAdapter->setCredential($options, $is_get);
        }

        return $options;
    }

    /**
     * Validate settings
     * 
     * NOTE: You can override this method appropriately.
     *
     * @return void
     * @throws ApiUrlNotFoundException
     */
    protected function validate(): void
    {
        if (empty($this->config->getEndpoint())) {
            throw ApiUrlNotFoundException::factory('Base URL is not configured.');
        }
    }

    /**
     * Replace using specified placeholders.
     * 
     * For example:
     * echo replace_placeholders(
     *     "/any/path/:action/:id",
     *     [':action' => 'show', ':uuid' => '1']
     * );
     * 
     * Result: /any/path/show/1
     *
     * @param string $subject
     * @return string
     */
    protected function replacePlaceholders(string $subject): string
    {
        if (empty($this->placeholders)) {
            return $subject;
        }

        return str_replace(
            array_keys($this->placeholders),
            array_values($this->placeholders),
            $subject
        );
    }
}
