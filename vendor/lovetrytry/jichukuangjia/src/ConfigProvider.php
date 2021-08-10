<?php

declare(strict_types=1);

namespace Lovetrytry\Jichukuangjia;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'listeners' => [

            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'collectors' => [

                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for lovetrytry jichukuangjia.',
                    'source' => __DIR__ . '/../publish/lovetrytry-jichukuangjia.php',
                    'destination' => BASE_PATH . '/config/autoload/lovetrytry-jichukuangjia.php',
                ],
            ],
        ];
    }
}
