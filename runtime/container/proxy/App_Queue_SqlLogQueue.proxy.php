<?php

declare (strict_types=1);
namespace App\Queue;

use App\Service\LogService;
use Hyperf\AsyncQueue\Job;
use Hyperf\Di\Annotation\Inject;
/**
 * Class SqlLogQueue
 * @package App\Queue
 * note:从新输出日志文件
 */
class SqlLogQueue extends Job
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    /**
     * @Inject
     * @var LogService
     */
    private LogService $logService;
    public $params;
    public function __construct($params)
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->params = $params;
    }
    public function handle()
    {
        foreach ($this->params as $param) {
            $this->logService->writeSqlLog($param);
        }
    }
}