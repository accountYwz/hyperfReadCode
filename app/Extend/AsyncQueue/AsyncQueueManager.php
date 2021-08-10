<?php

declare(strict_types=1);

namespace App\Extend\AsyncQueue;

use App\Extend\AsyncQueue\Exceptions\HostNotFoundException;
use App\Extend\AsyncQueue\Exceptions\JobPushFailException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class AsyncQueueManager
{
    private static ?Client $client = null;

    /**
     * @param string $jobName
     * @param array $jobParameter
     * @param int $delay
     * @param string $drive
     * @note 将任务推入队列
     * @author  fengpengyuan   2021/7/27
     * @email  py_feng@juling.vip
     * @modifier fengpengyuan 2021/7/27
     */
    public static function push(string $jobName, array $jobParameter = [], int $delay = 0, string $drive = 'default')
    {
        try {
            static::getClient()->post('queue/push', [
                RequestOptions::JSON => [
                    'jobName' => $jobName,
                    'jobParameter' => $jobParameter,
                    'delay' => $delay,
                    'drive' => $drive,
                ],
            ]);
        } catch (GuzzleException $e) {
            $message = 'async queue job push fail.';
            if ($e instanceof BadResponseException) {
                $resp = $e->getResponse();
                $contents = json_decode($resp->getBody()->getContents(), true);
                $message .= $contents['message'] ?? '';
            }
            throw new JobPushFailException($message);
        }
    }

    /**
     * @return Client
     * @note 得到静态的GuzzleHttp/client实例
     * @author  fengpengyuan   2021/7/27
     * @email  py_feng@juling.vip
     * @modifier fengpengyuan 2021/7/27
     */
    private static function getClient()
    {
        if (is_null(self::$client)) {
            $asyncQueueHost = env('ASYNC_QUEUE_HOST');
            if (is_null($asyncQueueHost)) {
                throw new HostNotFoundException();
            }
            self::$client = new Client(['base_uri' => "http://{$asyncQueueHost}/"]);
        }
        return self::$client;
    }
}
