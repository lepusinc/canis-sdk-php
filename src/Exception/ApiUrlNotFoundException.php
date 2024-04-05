<?php
declare(strict_types=1);

namespace Canis\Exception;

use Canis\Exception\Trait\Factoryable;

final class ApiUrlNotFoundException extends \RuntimeException
{
    use Factoryable;
}
