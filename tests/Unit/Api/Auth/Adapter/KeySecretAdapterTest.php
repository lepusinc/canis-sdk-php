<?php
declare(strict_types=1);

namespace Tests\Unit\Api\Auth\Adapter;

use Canis\Api\Auth\Adapter\KeySecretAdapter;
use Canis\Exception\KeySecretNotFoundException;
use PHPUnit\Framework\TestCase;

class KeySecretAdapterTest extends TestCase
{
    /**
     * @covers \Canis\Api\Auth\Adapter\KeySecretAdapter::validate
     */
    public function test_validate(): void
    {
        $adapter = new KeySecretAdapter('', '');
        
        $this->expectException(KeySecretNotFoundException::class);
        $adapter->validate();
    }

    /**
     * @covers \Canis\Api\Auth\Adapter\KeySecretAdapter::setCredential
     * @dataProvider data_setCredential
     */
    public function test_setCredential(
        bool $isGet
    ): void
    {
        $key = 'key';
        $secret = 'secret';
        $adapter = new KeySecretAdapter($key, $secret);
        $options = $adapter->setCredential([
            'key' => $key,
            'secret' => $secret,
        ], $isGet);

        $queryKeyName = $isGet ? 'query' : 'json';

        $this->assertEquals($key, $options[$queryKeyName]['client_id']);
        $this->assertEquals($secret, $options[$queryKeyName]['client_secret']);
    }

    public static function data_setCredential(): array
    {
        return [
            [false],
            [true],
        ];
    }
}