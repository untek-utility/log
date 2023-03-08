<?php

namespace Untek\Utility\Log\Infrastructure;

use Mservis\Operator\Shared\Infrastructure\Bundle\BaseBundle;

class LogBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'init';
    }

    public function boot(): void
    {
        
        $this->configureFromPhpFile(__DIR__ . '/../Domain/config/container.php');
    }
}
