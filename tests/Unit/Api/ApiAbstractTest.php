<?php
declare(strict_types=1);

namespace Tests\Unit\Api;

use Canis\Api\ApiAbstract;
use Canis\Api\Auth\Config;
use Canis\Api\Auth\Token;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;

class ApiAbstractTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected MockInterface $client;

    /**
     * @var \Mockery\MockInterface
     */
    protected MockInterface $psrResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(ClientInterface::class);
        $this->psrResponse = Mockery::mock(ResponseInterface::class);
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
        $this->markTestIncomplete();

        // $api = $this->getClass([
        //     'endpoint_url' => 'https://api.canis.io',
        // ]);

        // $this->client
        //     ->shouldReceive('request')
        //     ->once()
        //     ->with('GET', 'https://api.canis.io/v1user/profile/:uuid', [])
        //     ->andReturn($this->psrResponse);

        // $this->psrResponse
        //     ->shouldReceive('getBody')
        //     ->once()
        //     ->andReturn('[{"text":"foo"},{"text":"bar"}]');

        // $results = $api->sendRequest('GET', 'user/profile/:uuid', []);
    }
}