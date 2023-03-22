<?php

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Utility\Log\Domain\Interfaces\Repositories\LogRepositoryInterface;
use Untek\Utility\Log\Domain\Monolog\Handler\EloquentHandler;
use Untek\Utility\Log\Domain\Repositories\Eloquent\LogRepository;
use Untek\Core\App\Interfaces\EnvStorageInterface;
use Untek\Core\Text\Helpers\TemplateHelper;

return [
    'singletons' => [
        'Untek\Utility\Log\Domain\Interfaces\Repositories\HistoryRepositoryInterface' => 'Untek\Utility\Log\Domain\Repositories\Json\HistoryRepository',
        'Untek\Utility\Log\Domain\Interfaces\Services\HistoryServiceInterface' => 'Untek\Utility\Log\Domain\Services\HistoryService',

        LogRepositoryInterface::class => LogRepository::class,
        HandlerInterface::class => function (ContainerInterface $container) {
            /** @var EnvStorageInterface $envStorage */
            $envStorage = $container->get(EnvStorageInterface::class);

            $channel = 'application';
            $fileMask = getenv('LOG_FILE_MASK');
            $directory = getenv('LOG_DIRECTORY');
            $driver = getenv('LOG_DRIVER');

            if ($driver == 'file') {
                if($fileMask) {
                    $logFileMask = new LogFileMask($fileMask);
                    $logFileMask->addReplacement('channel', $channel);
                    $logFileMask->addReplacementFromTime();
                    $relativeFileName = $logFileMask->render();
                } else {
                    $relativeFileName = $channel . '.json';
                }
                $logFileName = $directory . '/' . $relativeFileName;
                $handler = new StreamHandler($logFileName);
            } elseif ($driver == 'stdout') {
                $logFileName = 'php://stdout';
                $handler = new StreamHandler($logFileName);
            } elseif ($driver == 'db') {
                /** @var AbstractProcessingHandler $handler */
                $handler = $container->get(EloquentHandler::class);
            } else {
                throw new Exception('Select log driver in env!');
            }



            $formatter = new JsonFormatter();
            $formatter->includeStacktraces();
            if (!getenv('APP_ENV') !== 'prod') {
                $formatter->setJsonPrettyPrint(true);
            }
            $handler->setFormatter($formatter);

            return $handler;
        },
        LoggerInterface::class => function (ContainerInterface $container) {
            /** @var EnvStorageInterface $envStorage */
            $envStorage = $container->get(EnvStorageInterface::class);

            $driver = $envStorage->get('LOG_DRIVER') ?: null;
            if ($driver == null) {
                $logger = new NullLogger();
            } else {
                $handler = $container->get(HandlerInterface::class);
                $level = EnvHelper::isDebug() ? Logger::DEBUG : Logger::ERROR;
                $handler->setLevel($level);
                $logger = new Logger('application');
                $logger->pushHandler($handler);
            }
            return $logger;
        },
    ],
];
