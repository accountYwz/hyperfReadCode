<?php

namespace App\Service;

use Psr\Container\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
class BaseService
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
}