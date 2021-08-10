<?php

declare (strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Listener;

use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
/**
 * @Listener
 */
class DbQueryExecutedListener implements ListenerInterface
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    /**
     * @var LoggerInterface
     */
    private $logger;
    public function __construct(ContainerInterface $container)
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->logger = $container->get(LoggerFactory::class)->get('sql');
    }
    public function listen() : array
    {
        return [QueryExecuted::class];
    }
    /**
     * @param QueryExecuted $event
     */
    public function process(object $event)
    {
        $__function__ = __FUNCTION__;
        $__method__ = __METHOD__;
        return self::__proxyCall(__CLASS__, __FUNCTION__, self::__getParamsMap(__CLASS__, __FUNCTION__, func_get_args()), function (object $event) use($__function__, $__method__) {
            if ($event instanceof QueryExecuted) {
                $sql = $event->sql;
                if (!Arr::isAssoc($event->bindings)) {
                    foreach ($event->bindings as $key => $value) {
                        $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                    }
                }
                $this->logger->info(sprintf('[%s] %s', $event->time, $sql));
                return $sql;
            }
        });
    }
}