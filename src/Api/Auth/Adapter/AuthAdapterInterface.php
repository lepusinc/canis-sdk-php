<?php
declare(strict_types=1);

namespace Canis\Api\Auth\Adapter;

interface AuthAdapterInterface
{
    /**
     * Set required credentials into request option
     * @param array<string,mixed> $options
     * @param bool $isGet (Default: false)
     * @return array<string,mixed>
     */
    public function setCredential(array $options, bool $isGet = false): array;

    /**
     * Validate credentials
     * @return void
     */
    public function validate(): void;
}