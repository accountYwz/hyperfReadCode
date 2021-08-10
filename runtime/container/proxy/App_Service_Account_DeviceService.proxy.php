<?php

declare (strict_types=1);
namespace App\Service\Account;

use App\Constants\ContextKey;
use App\Extend\Redis\DefaultRedis;
use App\Model\Main\User;
use App\Model\Manager\Appkey;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;
use Psr\SimpleCache\CacheInterface;
class DeviceService extends BaseService
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
     * 设备类型.
     */
    const DEVICE_PC = 'pc';
    const DEVICE_IOS = 'ios';
    const DEVICE_H5 = 'h5';
    const DEVICE_ANDROID = 'android';
    /** @Inject */
    protected RequestInterface $request;
    /**
     * token缓存前缀
     * @var string
     */
    protected string $tokenCacheKeyPrefix = 'token:';
    /**
     * 各设备token过期时间.
     * @var array|int[]
     */
    protected array $ttlConfig = [self::DEVICE_PC => 86400, self::DEVICE_IOS => 2592000, self::DEVICE_H5 => 86400, self::DEVICE_ANDROID => 2592000];
    /**
     * @func 生成token
     * @param int $uid 用户ID
     * @return array
     * @author luzhenyu 2021/6/16
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/7/9
     */
    public function createToken(int $uid) : array
    {
        $device = $this->getDevice();
        $userData = User::query()->select(['id', 'account', 'status'])->where('id', $uid)->first();
        $userData = $userData ? $userData->toArray() : [];
        $token = uniqid();
        $cache = $this->container->get(CacheInterface::class);
        $cache->set($this->getCacheKey($token), $userData, $this->ttlConfig[$device]);
        $redis = $this->container->get(DefaultRedis::class);
        $tokenSavePosition = DefaultRedis::USER_TOKEN . ':' . $uid;
        $cache->delete($this->getCacheKey($redis->hGet($tokenSavePosition, $device)));
        $redis->hSet($tokenSavePosition, $device, $token);
        $tokenInfo = ['token' => $token, 'tokenExpireSec' => $this->ttlConfig[$device]];
        Context::set(ContextKey::TOKEN_INFO, $tokenInfo);
        return $tokenInfo;
    }
    /**
     * @return array
     * @func 获取登录后生成的token信息
     * @author luzhenyu 2021/8/5
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/8/5
     */
    public function getTokenInfo() : array
    {
        $tokenInfo = Context::get(ContextKey::TOKEN_INFO);
        return ['token' => $tokenInfo['token'] ?? '', 'tokenExpireSec' => $tokenInfo['tokenExpireSec'] ?? 0];
    }
    /**
     * @func 校验token
     * @param string $token
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return bool
     * @author luzhenyu 2021/6/16
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/7/9
     */
    public function checkToken(string $token) : array
    {
        if (!$token) {
            return [];
        }
        $cache = $this->container->get(CacheInterface::class);
        return $cache->get($this->getCacheKey($token), []);
    }
    /**
     * @func 获取设备
     * @author luzhenyu 2021/7/19
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/7/19
     */
    public function getDevice() : string
    {
        $appId = $this->request->input('appid');
        $deviceType = Appkey::query()->where(['key' => $appId, 'is_del' => 0])->value('device_type');
        switch ($deviceType) {
            case 1:
                $device = self::DEVICE_PC;
                break;
            case 2:
                $device = self::DEVICE_H5;
                break;
            case 3:
                $device = self::DEVICE_IOS;
                break;
            case 4:
                $device = self::DEVICE_ANDROID;
                break;
            default:
                $device = self::DEVICE_PC;
        }
        return $device;
    }
    /**
     * @func token缓存键值前缀
     * @param string $key
     * @return string
     * @author luzhenyu 2021/7/9
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/7/9
     */
    protected function getCacheKey(string $key) : string
    {
        return $this->tokenCacheKeyPrefix . $key;
    }
}