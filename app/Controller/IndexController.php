<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;
use App\Controller\Bar;
use Hyperf\Di\Annotation\AnnotationCollector;

/**
 * Class IndexController
 * @package App\Controller
 * @Bar(name="test",age="100");
 */
class IndexController extends AbstractController
{
    public function index()
    {
        var_dump(1000);
        $classByAnnotation = AnnotationCollector::getClassesByAnnotation(Bar::class);
        var_dump($classByAnnotation);
        $name = $classByAnnotation[IndexController::class]->name;
        $age =  $classByAnnotation[IndexController::class]->age;
        var_dump($name,$age);
    }
}
