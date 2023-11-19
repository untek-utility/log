<?php

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\Log\Infrastructure\Factories\LoggerFactory;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $parameters = $configurator->parameters();

    $services->set(LoggerInterface::class, Logger::class)
        ->factory([LoggerFactory::class, 'makeDefaultLogger'])
        ->args(
            [
                'default'
            ]
        );
};