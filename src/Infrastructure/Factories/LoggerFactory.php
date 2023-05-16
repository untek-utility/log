<?php

namespace Untek\Utility\Log\Infrastructure\Factories;

use InvalidArgumentException;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Untek\Utility\Log\Domain\Libs\LogFileMask;

final class LoggerFactory
{
    public static function makeDefaultLogger(string $loggerName): LoggerInterface
    {
        if (true === empty($loggerName)) {
            throw new InvalidArgumentException('You must specify non empty logger name');
        }

        $driver = getenv('LOG_DRIVER');

        if ($driver == 'null') {
            $logger = new NullLogger();
        } elseif ($driver == 'file') {
            $logger = self::createHandler($loggerName);
        } elseif ($driver == 'stdout') {
            $logger = self::createStdHandler($loggerName);
        } else {
            $logger = new NullLogger();
//            throw new Exception('Select log driver in env!');
        }

        return $logger;
    }

    protected static function createStdHandler(string $loggerName): LoggerInterface
    {
        $stdOutHandler = new StreamHandler('php://stdout', Logger::DEBUG);
        $stdOutFilterHandler = new FilterHandler(
            $stdOutHandler,
            Logger::DEBUG,
            Logger::NOTICE
        );

        $stdErrHandler = new StreamHandler('php://stderr', Logger::WARNING);

        $logger = new Logger($loggerName);

        $logger->pushHandler($stdOutFilterHandler);
        $logger->pushHandler($stdErrHandler);

        return $logger;
    }

    protected static function createHandler(string $loggerName): LoggerInterface
    {
        $channel = 'application';
        $fileMask = getenv('LOG_FILE_MASK');
        $directory = getenv('LOG_DIRECTORY');

        if ($fileMask) {
            $logFileMask = new LogFileMask($fileMask);
            $logFileMask->addReplacement('channel', $channel);
            $logFileMask->addReplacementFromTime();
            $relativeFileName = $logFileMask->render();
            $logFileName = $directory . '/' . $relativeFileName;
        } else {
            $relativeFileName = $channel . '.json';
            $logFileName = $directory . '/' . $relativeFileName;
        }

        $handler = new StreamHandler($logFileName);

        $formatter = new JsonFormatter();
        $formatter->includeStacktraces();
        if (!getenv('APP_ENV') !== 'prod') {
            $formatter->setJsonPrettyPrint(true);
        }
        $handler->setFormatter($formatter);

        $logger = new Logger($loggerName);

        $logger->pushHandler($handler);

        return $logger;
    }
}