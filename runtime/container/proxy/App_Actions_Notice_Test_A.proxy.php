<?php

declare (strict_types=1);
namespace App\Actions\Notice\Test;

use App\Service\QueueService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Actions\AbstractAction;
/**
 * @AutoController
 */
class A extends AbstractAction
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct(...func_get_args());
        }
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var QueueService
     */
    protected $service;
    /**
     * 传统模式投递消息
     */
    public function handle()
    {
        $this->service->push(['group@hyperf.io', 'https://doc.hyperf.io', 'https://www.hyperf.io']);
        return 'success';
    }
}