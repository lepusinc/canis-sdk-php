<?php
declare(strict_types=1);

namespace Tests\Unit\Api\AuthAdapter;

use Canis\Api\AuthAdapter\KeySecretAdapter;
use Canis\Api\AuthAdapter\TokenAdapter;
use Canis\Exception\KeySecretNotFoundException;
use Canis\Exception\TokenNotFoundException;
use PHPUnit\Framework\TestCase;

class TokenAdapterTest extends TestCase
{
    /**
     * @covers \Canis\Api\AuthAdapter\TokenAdapter::validate
     */
    public function test_validate(): void
    {
        $adapter = new TokenAdapter('');
        
        $this->expectException(TokenNotFoundException::class);
        $adapter->validate();
    }

    /**
     * @covers \Canis\Api\AuthAdapter\TokenAdapter::setCredential
     */
    public function test_setCredential(): void
    {
        $token = 'abcdefghijklmnopqrstuvwxyz';
        $adapter = new TokenAdapter($token);
        $options = $adapter->setCredential([
            'token' => $token,
        ]);

        $this->assertEquals(
            "Bearer {$token}",
            $options['headers']['Authorization']
        );
    }
}