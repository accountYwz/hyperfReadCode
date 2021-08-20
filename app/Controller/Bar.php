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

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Class Bar
 * @package App\Controller
 * @Annotation
 * @Target({"CLASS"})
 */
class Bar extends AbstractAnnotation
{
    public $age ;
    public $name;
}
