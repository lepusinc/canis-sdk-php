<?php
declare(strict_types=1);

namespace Tests\Unit\Api;

use Canis\Api\ApiAbstract;
use Canis\Api\Auth\Config;
use Canis\Api\Auth\Token;
use Canis\Exception\ApiHttpErrorException;
use Canis\Exception\ApiUrlNotFoundException;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ApiAbstractTest extends TestCase
{
    /**
     * @covers \Canis\Api\ApiAbstract::__construct
     */
    public function test_construct(): void
    {
        $endpoint = 'https://example.com/api/v1';
        $token = 'abcdefghijklmnopqrstuvwxyz';

        $api = $this->getClass(
            endpoint: $endpoint,
            token: Token::factory()->setToken($token),
        );

        $this->assertEquals($endpoint, $api->config->getEndpoint());
        $this->assertEquals($token, $api->config->getToken()->getToken());
        
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $api->getHttpClient());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::init
     */
    public function test_init(): void
    {
        $api = $this->getClass()->init();

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $api->getHttpClient());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::getHttpClient
     */
    public function test_getHttpClient(): void
    {
        $api = $this->getClass();

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $api->getHttpClient());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::setPlaceholders
     */
    public function test_setPlaceholders(): void
    {
        $api = $this->getClass();

        $this->assertEquals(
            [':key' => 'value'],
            $api->setPlaceholders([':key' => 'value'])->placeholders
        );
    }

    /**
     * @covers \Canis\Api\ApiAbstract::get
     * @see https://docs.guzzlephp.org/en/stable/testing.html
     */
    public function test_get(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mockHandler = $this->getMockHandler(status: 200);
        $mockHandler->push($history);

        $api = $this->getClass(
            endpoint: 'https://example.com/api',
        );

        $results = $api->get(
            '/user/profile/:uuid',
            ['foo' => 'bar'],
            ['handler' => $mockHandler],
        );

        $transaction = $container[0];
        $this->assertIsArray($transaction);

        /** @var array<int,\GuzzleHttp\Psr7\Request>|
         * array<int,\GuzzleHttp\Psr7\Response> $transaction */
        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertEquals(
            'https://example.com/api/' .
                ApiAbstract::API_VERSION .
                '/user/profile/:uuid?foo=bar',
            (string) $transaction['request']->getUri()
        );
        $this->assertEquals(200, $transaction['response']->getStatusCode());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::post
     * @see https://docs.guzzlephp.org/en/stable/testing.html
     */
    public function test_post(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mockHandler = $this->getMockHandler(status: 200);
        $mockHandler->push($history);

        $api = $this->getClass(
            endpoint: 'https://example.com/api',
        );

        $api->post(
            '/user/profile/:uuid',
            ['foo' => 'bar'],
            ['handler' => $mockHandler],
        );

        $transaction = $container[0];
        $this->assertIsArray($transaction);

        /** @var array<int,\GuzzleHttp\Psr7\Request>|
         * array<int,\GuzzleHttp\Psr7\Response> $transaction */
        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertEquals(
            'https://example.com/api/' .
                ApiAbstract::API_VERSION .
                '/user/profile/:uuid',
            (string) $transaction['request']->getUri()
        );
        $this->assertEquals(
            'application/json',
            $transaction['request']->getHeader('Content-Type')[0]
        );
        $this->assertEquals(200, $transaction['response']->getStatusCode());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::put
     * @see https://docs.guzzlephp.org/en/stable/testing.html
     */
    public function test_put(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mockHandler = $this->getMockHandler(status: 200);
        $mockHandler->push($history);

        $api = $this->getClass(
            endpoint: 'https://example.com/api',
        );

        $api->put(
            '/user/profile/:uuid',
            ['foo' => 'bar'],
            ['handler' => $mockHandler],
        );

        $transaction = $container[0];
        $this->assertIsArray($transaction);

        /** @var array<int,\GuzzleHttp\Psr7\Request>|
         * array<int,\GuzzleHttp\Psr7\Response> $transaction */
        $this->assertEquals('PUT', $transaction['request']->getMethod());
        $this->assertEquals(
            'https://example.com/api/' .
                ApiAbstract::API_VERSION .
                '/user/profile/:uuid',
            (string) $transaction['request']->getUri()
        );
        $this->assertEquals(
            'application/json',
            $transaction['request']->getHeader('Content-Type')[0]
        );
        $this->assertEquals(200, $transaction['response']->getStatusCode());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::put
     * @see https://docs.guzzlephp.org/en/stable/testing.html
     */
    public function test_delete(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mockHandler = $this->getMockHandler(status: 200);
        $mockHandler->push($history);

        $api = $this->getClass(
            endpoint: 'https://example.com/api',
        );

        $api->delete(
            '/user/profile/:uuid',
            ['foo' => 'bar'],
            ['handler' => $mockHandler],
        );

        $transaction = $container[0];
        $this->assertIsArray($transaction);

        /** @var array<int,\GuzzleHttp\Psr7\Request>|
         * array<int,\GuzzleHttp\Psr7\Response> $transaction */
        $this->assertEquals('DELETE', $transaction['request']->getMethod());
        $this->assertEquals(
            'https://example.com/api/' .
                ApiAbstract::API_VERSION .
                '/user/profile/:uuid',
            (string) $transaction['request']->getUri()
        );
        $this->assertEquals(
            'application/json',
            $transaction['request']->getHeader('Content-Type')[0]
        );
        $this->assertEquals(200, $transaction['response']->getStatusCode());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::sendRequest
     */
    public function test_sendRequest_ApiUrlNotFoundException(): void
    {
        $api = $this->getClass();

        $this->expectException(\Canis\Exception\ApiUrlNotFoundException::class);

        $api->sendRequest('GET', '/user/profile/:uuid');
    }

    /**
     * @covers \Canis\Api\ApiAbstract::sendRequest
     */
    public function test_sendRequest_ApiHttpErrorException(): void
    {
        $api = $this->getClass(
            endpoint: 'https://example.com/api/v1',
        );

        // Assert 4xx error.
        $this->expectException(ApiHttpErrorException::class);
        $this->expectExceptionMessage('Not Found');

        $api->sendRequest(
            'GET',
            'user/profile/:uuid',
            ['handler' => $this->getMockHandler(
                status: 404,
                body: 'Not Found',
            )],
        );

        // Assert 5xx error.
        $this->expectException(ApiHttpErrorException::class);
        $this->expectExceptionMessage('Service Unavailable');

        $api->sendRequest(
            'GET',
            'user/profile/:uuid',
            ['handler' => $this->getMockHandler(
                status: 503,
                body: 'Service Unavailable',
            )],
        );
    }

    /**
     * @covers \Canis\Api\ApiAbstract::sendRequest
     */
    public function test_sendRequest(): void
    {
        $api = $this->getClass(
            endpoint: 'https://example.com/api/v1',
        );

        $results = $api->sendRequest(
            'GET',
            'user/profile/:uuid',
            ['handler' => $this->getMockHandler(
                body: json_encode(['text' => 'foo']),
            )],
        );

        // Assert that the response is returned as an array.
        $this->assertEquals(['text' => 'foo'], $results);
    }

    /**
     * @covers \Canis\Api\ApiAbstract::withKeySecretAdapter
     */
    public function test_withKeySecretAdapter(): void
    {
        $api = $this->getClass(
            key: 'key',
            secret: 'secret',
        );
        $api->withKeySecretAdapter();

        /** @var \Canis\Api\Auth\Adapter\KeySecretAdapter $adapter */
        $adapter = $api->getAdapter();
        $this->assertInstanceOf(\Canis\Api\Auth\Adapter\KeySecretAdapter::class, $adapter);
        $this->assertEquals('key', $adapter->key);
        $this->assertEquals('secret', $adapter->secret);
    }

    /**
     * @covers \Canis\Api\ApiAbstract::withTokenAdapter
     */
    public function test_withTokenAdapter(): void
    {
        $api = $this->getClass(
            token: Token::factory(token: 'abcdefghijklmnopqrstuvwxyz'),
        );
        $api->withTokenAdapter();

        /** @var \Canis\Api\Auth\Adapter\TokenAdapter $adapter */
        $adapter = $api->getAdapter();
        $this->assertInstanceOf(\Canis\Api\Auth\Adapter\TokenAdapter::class, $adapter);
        $this->assertEquals('abcdefghijklmnopqrstuvwxyz', $adapter->token->getToken());
    }

    public function test_setCredential_KeySecret_Get(): void
    {
        $api = $this->getClass(
            key: 'key',
            secret: 'secret',
        )->withKeySecretAdapter();

        $reflection = new ReflectionClass($api);
        $method = $reflection->getMethod('setCredential');
        $result = $method->invoke($api, ['foo' => 'bar'], 'GET');

        /**
         * Assert that in case that method is GET,
         * key and secret are set in 'query' field.
         */
        $this->assertEquals(
            [
                'foo' => 'bar',
                'query' => ['client_id' => 'key', 'client_secret' => 'secret'],
            ], 
            $result
        );
    }

    public function test_setCredential_KeySecret_Post(): void
    {
        $api = $this->getClass(
            key: 'key',
            secret: 'secret',
        )->withKeySecretAdapter();

        $reflection = new ReflectionClass($api);
        $method = $reflection->getMethod('setCredential');
        $result = $method->invoke($api, ['foo' => 'bar'], 'POST');

        /**
         * Assert that in case that method is not GET,
         * key and secret are set in 'json' field.
         */
        $this->assertEquals(
            [
                'foo' => 'bar',
                'json' => ['client_id' => 'key', 'client_secret' => 'secret'],
            ], 
            $result
        );
    }

    public function test_setCredential_Token(): void
    {
        $api = $this->getClass(
            token: Token::factory(token: 'abcdefghijklmnopqrstuvwxyz'),
        )->withTokenAdapter();

        $reflection = new ReflectionClass($api);
        $method = $reflection->getMethod('setCredential');
        // 2nd argument is no matter in this case.
        $result = $method->invoke($api, ['foo' => 'bar'], 'GET');

        /**
         * Assert that in case that method is GET,
         * key and secret are set in 'query' field.
         */
        $this->assertEquals(
            [
                'foo' => 'bar',
                'headers' => [
                    'Authorization' => 'Bearer abcdefghijklmnopqrstuvwxyz',
                ],
            ], 
            $result
        );
    }

    public function test_validate_exception(): void
    {
        $api = $this->getClass(
            key: 'key',
            secret: 'secret',
        )->withKeySecretAdapter();
        
        // Assert that exception is thrown.
        $this->expectException(ApiUrlNotFoundException::class);

        $reflection = new ReflectionClass($api);
        $method = $reflection->getMethod('validate');
        $method->invoke($api);
    }

    public function test_validate_noException(): void
    {
        $api = $this->getClass(
            endpoint: 'https://example.com/api',
            key: 'key',
            secret: 'secret',
        )->withKeySecretAdapter();
        
        $reflection = new ReflectionClass($api);
        $method = $reflection->getMethod('validate');
        $method->invoke($api);

        // Assert that exception is NOT thrown.
        $this->assertTrue(true);
    }

    public function test_replacePlaceholders(): void
    {
        $api = $this->getClass();

        $api->setPlaceholders([':action' => 'show', ':id' => '1']);;
        $this->assertEquals(
            "/any/path/show/1",
            $api->replacePlaceholders(
                "/any/path/:action/:id"
            )
        );
    }

    /**
     * Create mock handler.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getMockHandler(
        int $status = 200,
        array $headers = [],
        string $body = ''
    ): \GuzzleHttp\HandlerStack
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response($status, $headers, $body),
        ]);

        return \GuzzleHttp\HandlerStack::create($mock);
    }

    /**
     * Return an instance of the class.
     * 
     * @param string $endpoint
     * @param Token|null $token
     * @param string $key
     * @param string $secret
     * @param array $options
     * @return \Canis\Api\ApiAbstract
     */
    private function getClass(
        string $endpoint = '',
        ?Token $token = null,
        string $key = '',
        string $secret = '',
        array $options = []
    ): ApiAbstract
    {
        $config = $this->getConfig(
            endpoint: $endpoint,
            token: $token ?? Token::factory(),
            key: $key,
            secret: $secret,
            options: $options,
        );

        return new class($config) extends \Canis\Api\ApiAbstract {
            /**
             * @param Config $config
             */
            public function __construct(Config $config)
            {
                parent::__construct($config);
            }
        };
    }

    /**
     * Return an instance of the Config class.
     * 
     * @param string $endpoint
     * @param Token|null $token
     * @param string $key
     * @param string $secret
     * @param array $options
     * @return \Canis\Api\Auth\Config
     */
    private function getConfig(
        string $endpoint = '',
        ?Token $token = null,
        string $key = '',
        string $secret = '',
        array $options = []
    ): Config
    {
        return new \Canis\Api\Auth\Config(
            endpoint: $endpoint,
            token: $token ?? Token::factory(),
            key: $key,
            secret: $secret,
            options: $options,
        );
    }
}