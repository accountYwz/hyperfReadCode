<?php

declare (strict_types=1);
namespace App\Service;

use App\Service\QueueService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Actions\AbstractAction;
/**
 * @AutoController
 */
class WriteSqlLogQueueService extends AbstractAction
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    /**
     * @var DriverInterface
     */
    protected $driver;
    public function __construct(DriverFactory $driverFactory)
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->driver = $driverFactory->get('default');
    }
    /**
     * @Inject
     * @var QueueService
     */
    protected $service;
    /**
     * 传统模式投递消息
     */
    public function index()
    {
        $this->service->push(['group@hyperf.io', 'https://doc.hyperf.io', 'https://www.hyperf.io']);
        return 'success';
    }
}