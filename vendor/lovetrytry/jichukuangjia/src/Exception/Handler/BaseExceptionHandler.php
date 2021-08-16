<?php

declare(strict_types=1);

namespace Lovetrytry\Jichukuangjia\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Logger\LoggerFactory;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Exception for signaling runtime errors.
 *
 * @author     Xing Jiapeng
 * @since      2021-06-01 16:32:48(+0800)
 */
class BaseExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $errorLogger;

    public function __construct(StdoutLoggerInterface $logger, LoggerFactory $loggerFactory)
    {
        $this->logger = $logger;
        $this->errorLogger = $loggerFactory->get('log', 'error');
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof BusinessException) {
            $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
            $this->logger->error($throwable->getTraceAsString());
        } else {
            $this->errorLogger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
            $this->errorLogger->error($throwable->getTraceAsString());
        }
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($throwable->getCode())
                        ->withBody(
                            new SwooleStream($throwable->getMessage())
                        );
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}