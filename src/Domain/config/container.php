<?php

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Utility\Log\Domain\Libs\LogFileMask;

return function (ContainerConfiguratorInterface $containerConfigurator) {
    $containerConfigurator->bind(
        HandlerInterface::class,
        function (ContainerInterface $container) {
            $channel = 'application';
            $fileMask = getenv('LOG_FILE_MASK');
            $directory = getenv('LOG_DIRECTORY');
            $driver = getenv('LOG_DRIVER');

            if ($fileMask && $driver == 'file') {
                $logFileMask = new LogFileMask($fileMask);
                $logFileMask->addReplacement('channel', $channel);
                $logFileMask->addReplacementFromTime();
                $relativeFileName = $logFileMask->render();
                $logFileName = $directory . '/' . $relativeFileName;
            } elseif ($driver == 'file') {
                $relativeFileName = $channel . '.json';
                $logFileName = $directory . '/' . $relativeFileName;
            } elseif ($driver == 'stdout') {
                $logFileName = 'php://stdout';
            } elseif ($driver == 'db') {
                /** @var AbstractProcessingHandler $handler */
                $handler = $container->get(EloquentHandler::class);
            } else {
                throw new Exception('Select log driver in env!');
            }

            $handler = new StreamHandler($logFileName);

            $formatter = new JsonFormatter();
            $formatter->includeStacktraces();
            if (!getenv('APP_ENV') !== 'prod') {
                $formatter->setJsonPrettyPrint(true);
            }
            $handler->setFormatter($formatter);

            return $handler;
        }
    );

    $containerConfigurator->bind(
        LoggerInterface::class,
        function (ContainerInterface $container) {
            $driver = getenv('LOG_DRIVER') ?: null;
            if ($driver == null) {
                $logger = new NullLogger();
            } else {
                $handler = $container->get(HandlerInterface::class);
                $level = getenv('APP_DEBUG') ? Logger::DEBUG : Logger::ERROR;
                $handler->setLevel($level);
                $logger = new Logger('application');
                $logger->pushHandler($handler);
            }
            return $logger;
        }
    );
};
