<?php

namespace Untek\Utility\Log\Infrastructure;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class LogBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'log';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../Resources/config/services/logger.php');
    }
}
