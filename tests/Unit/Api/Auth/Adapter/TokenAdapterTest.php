<?php
declare(strict_types=1);

namespace Tests\Unit\Api\Auth\Adapter;

use Canis\Api\Auth\Adapter\TokenAdapter;
use Canis\Api\Auth\Token;
use Canis\Exception\TokenInvalidException;
use PHPUnit\Framework\TestCase;

class TokenAdapterTest extends TestCase
{
    /**
     * @covers \Canis\Api\Auth\Adapter\TokenAdapter::validate
     */
    public function test_validate(): void
    {
        $adapter = new TokenAdapter(Token::factory()->setToken(''));
        
        $this->expectException(TokenInvalidException::class);
        $adapter->validate();
    }

    /**
     * @covers \Canis\Api\Auth\Adapter\TokenAdapter::setCredential
     */
    public function test_setCredential(): void
    {
        $token = 'abcdefghijklmnopqrstuvwxyz';
        $adapter = new TokenAdapter(Token::factory()->setToken($token));
        $options = $adapter->setCredential([
            'token' => $token,
        ]);

        $this->assertEquals(
            "Bearer {$token}",
            $options['headers']['Authorization']
        );
    }
}