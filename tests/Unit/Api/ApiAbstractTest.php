<?php
declare(strict_types=1);

namespace Tests\Unit\Api;

use Canis\Api\ApiAbstract;
use Canis\Api\Auth\Config;
use Canis\Api\Auth\Token;
use PHPUnit\Framework\TestCase;

class ApiAbstractTest extends TestCase
{
    /**
     * @covers \Canis\Api\ApiAbstract::__construct
     */
    public function test_construct(): void
    {
        $endpoint = 'https://api.canis.io';
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
    public function test_sendRequest(): void
    {
        $api = $this->getClass(
            endpoint: 'https://api.canis.io',
        );

        $results = $api->sendRequest(
            'GET',
            'user/profile/:uuid',
            ['handler' => $this->getMockHandler(
                body: json_encode(['text' => 'foo']),
            )],
        );

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