<?php
return [
    'consumers' => value(function () {
        $consumers = [];
        $services = [
            'WeixinAccountService' => App\Service\WeixinAccountService::class,
            'CalculatorService' => App\Service\CalculatorServiceInterface::class,
        ];
        foreach ($services as $name => $interface) {
            $consumers[] = [
                'name' => $name,
                'service' => $interface,
                'nodes' => [
                    ['host' => 'juling-msb-05', 'port' => 23333],
                ],
                // 配置项，会影响到 Packer 和 Transporter
                'options' => [
                    'connect_timeout' => 5.0,
                    'recv_timeout' => 5.0,
                    'settings' => [
                        // 根据协议不同，区分配置
                        'open_eof_split' => true,
                        'package_eof' => "\r\n",
                        // 'open_length_check' => true,
                        // 'package_length_type' => 'N',
                        // 'package_length_offset' => 0,
                        // 'package_body_offset' => 4,
                    ],
                    // 重试次数，默认值为 2，收包超时不进行重试。暂只支持 JsonRpcPoolTransporter
                    'retry_count' => 2,
                    // 重试间隔，毫秒
                    'retry_interval' => 100,
                    // 当使用 JsonRpcPoolTransporter 时会用到以下配置
                    'pool' => [
                        'min_connections' => 1,
                        'max_connections' => 32,
                        'connect_timeout' => 10.0,
                        'wait_timeout' => 3.0,
                        'heartbeat' => -1,
                        'max_idle_time' => 60.0,
                    ],
                ],
            ];
        }
        return $consumers;
    }),
];