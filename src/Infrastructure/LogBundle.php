<?php

namespace Untek\Utility\Log\Infrastructure;

use Untek\Core\Kernel\Bundle\BaseBundle;

class LogBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'log';
    }

    public function boot(): void
    {
        $this->configureFromPhpFile(__DIR__ . '/../Domain/config/container.php');
    }
}
