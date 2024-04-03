<?php
declare(strict_types=1);

namespace Tests\Unit\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\RequestInterface;
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
     * @param array<string,string> $config
     */
    private function getClass(array $config)
    {
        return new class($config) extends \Canis\Api\ApiAbstract {
            /**
             * @param array<string,string> $config
             */
            public function __construct(array $config)
            {
                parent::__construct($config);
            }
        };
    }

    /**
     * @covers \Canis\Api\ApiAbstract::init
     */
    public function test_init(): void
    {
        $api = $this->getClass([]);
        $config = [
            'endpoint_url' => 'https://api.canis.io',
            'timeout' => 10,
            'token' => 'abcdefghijklmnopqrstuvwxyz',
        ];
        $result = $api->init($config);

        $this->assertArrayHasKey('endpoint_url', $api->config);
        $this->assertArrayHasKey('timeout', $api->config);
        $this->assertArrayHasKey('token', $api->config);
        $this->assertArrayHasKey('key', $api->config);
        $this->assertArrayHasKey('secret', $api->config);

        $this->assertEquals($config['endpoint_url'], $api->config['endpoint_url']);
        $this->assertEquals($config['timeout'], $api->config['timeout']);
        $this->assertEquals($config['token'], $api->config['token']);

        $this->assertEquals($config['endpoint_url'], $api->endpointUrl);
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $api->getHttpClient());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::resolveConfig
     */
    public function test_resolveConfig(): void
    {
        $api = $this->getClass([]);
        $config = [
            'endpoint_url' => 'https://api.canis.io',
            'timeout' => 10,
            'token' => 'abcdefghijklmnopqrstuvwxyz',
        ];
        $result = $api->resolveConfig($config);

        $this->assertArrayHasKey('endpoint_url', $result);
        $this->assertArrayHasKey('timeout', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('secret', $result);

        $this->assertEquals($config['endpoint_url'], $result['endpoint_url']);
        $this->assertEquals($config['timeout'], $result['timeout']);
        $this->assertEquals($config['token'], $result['token']);
    }

    /**
     * @covers \Canis\Api\ApiAbstract::getHttpClient
     */
    public function test_getHttpClient(): void
    {
        $api = $this->getClass([]);

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $api->getHttpClient());
    }

    /**
     * @covers \Canis\Api\ApiAbstract::setPlaceholders
     */
    public function test_setPlaceholders(): void
    {
        $api = $this->getClass([]);

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
        $api = $this->getClass([]);

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