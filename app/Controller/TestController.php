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
 * Class TestControllers
 * @package App\Controller
 * @Bar(name="test1111",age="testtest100");
 */
class TestController extends AbstractController
{
    public function index()
    {
        var_dump(1000);
        $classByAnnotation = AnnotationCollector::getClassesByAnnotation(Bar::class);
        $name = $classByAnnotation[self::class]->name;
        $age =  $classByAnnotation[self::class]->age;
        var_dump($name,$age);
    }
}
