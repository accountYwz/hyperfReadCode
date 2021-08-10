<?php

namespace App\Service;

use App\Constants\CodeMsg;
use Psr\SimpleCache\CacheInterface;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
class ApiCheckService
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->__handlePropertyHandler(__CLASS__);
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        //logger 的名称，当前日期 api_check
        $this->logger = $loggerFactory->get('apiCheckService', 'default');
    }
    /**
     * 重放攻击 redis key 前缀
     */
    const REDIS_KEY_REPLAY_ATTACK = 'RY::';
    /**
     * 默认过期时间（秒）
     */
    const REDIS_DEFAULT_EXPIRE_TIME = 3600;
    /**
     * 允许的时间戳参数与服务器的差值 秒
     */
    const TIMESTAMP_DIFF_SEC = 15;
    /**
     * 根据appId 获取 key
     * 
     * @param string $appId  appId
     * @return string
     * @author l_yu 2021/6/24
     * @email l_yu@juling.vip
     */
    public function getAppKey($appId)
    {
        //此处待修改，根据appId 获取key，从redis中获取
        //如果未找到，返回'';
        return 'juling885656589999';
    }
    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && !is_array($v) && strlen($v) != 0) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * 生成签名
     * @param  $data
     * @return string
     */
    public function makeSign($data, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->ToUrlParams($data);
        //签名步骤二：在string后加入KEY
        //根据appKey 获取到  appSecret，当做key来处理
        $string = $string . "&key=" . $key;
        $this->logger->info('sign string::=======::' . $string);
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     *  验签函数，根据appKey得到AppSecret，
     *  用Appsecret,nonce,CurTime 进行运算  得到 CheckSum 与参数中的checkSum比较看是否通过验签
     * 
     * @param array $params  请求参数数组
     * @return array
     * @author l_yu 2021/6/24
     * @email l_yu@juling.vip
     */
    public function checkSignature($params)
    {
        $res = [];
        //需要的4个参数  appid,nonce_str,time_stamp,sign
        //header 参数有可能会自动设置为小写
        $appId = isset($params['appid']) ? $params['appid'] : '';
        $nonceStr = isset($params['nonce_str']) ? $params['nonce_str'] : '';
        $timeStamp = isset($params['time_stamp']) ? $params['time_stamp'] : '';
        $sign = isset($params['sign']) ? $params['sign'] : '';
        //参数如果缺失,报错
        if (empty($appId) || empty($nonceStr) || empty($timeStamp) || empty($sign)) {
            $res['res'] = false;
            $res['errorCode'] = CodeMsg::APICHECK_PARAM_LOST_ERROE;
            return $res;
        }
        //curTime 与服务器时间不超过 TIMESTAMP_DIFFSEC
        $timeStamp = (int) $timeStamp;
        $serverTime = time();
        if (abs($serverTime - $timeStamp) > self::TIMESTAMP_DIFF_SEC) {
            $res['res'] = false;
            $res['errorCode'] = CodeMsg::APICHECK_EXPIRE_ERROE;
            return $res;
        }
        //根据appKey 获取 appSecret， 如果获取不到，返回appKey 错误
        $key = $this->getAppKey($appId);
        if (empty($key)) {
            $res['res'] = false;
            $res['errorCode'] = CodeMsg::APICHECK_APPKEY_ERROE;
            return $res;
        }
        //2021/07/13 所有参数都来，按照字典序排序，然后算出sign
        $serverSign = $this->makeSign($params, $key);
        if ($serverSign != $sign) {
            //APICHECK_SIGN_ERROE
            //写入log
            $this->logger->error('sign data::=======::' . json_encode($params));
            $this->logger->error('right sign::=======::' . $serverSign . 'wrong sign:' . $sign);
            $res['res'] = false;
            $res['errorCode'] = CodeMsg::APICHECK_SIGN_ERROE;
            return $res;
        }
        //验签ok
        $res['res'] = true;
        return $res;
    }
    /**
     * 校验重放攻击
     * @return 成功时正常，其他抛异常
     * @author liu
     */
    public function checkReplayAttack($params, $intExpireTime = self::REDIS_DEFAULT_EXPIRE_TIME)
    {
        $res = [];
        $nonce = isset($params['nonce_str']) ? $params['nonce_str'] : '';
        $checkSum = isset($params['sign']) ? $params['sign'] : '';
        $strKey = self::REDIS_KEY_REPLAY_ATTACK . $nonce . '_' . $checkSum;
        //$redis = RedisManager::getInstance();
        //（通过Redis setnx指令实现，从Redis 2.6.12开始，通过set指令可选参数也可以实现setnx，同时可原子化地设置超时时间）
        //$result = $redis->set($strKey, 1, ['nx', 'ex' => $intExpireTime]);
        $cache = $this->container->get(CacheInterface::class);
        $checkResult = $cache->has($strKey);
        if ($checkResult) {
            $res['res'] = false;
            $res['errorCode'] = CodeMsg::APICHECK_REPEAT_ERROE;
            return $res;
        }
        $cache->set($strKey, 1, $intExpireTime);
        $res['res'] = true;
        return $res;
    }
    /**
     * 清空cache
     *
     * @param string $key
     * @return int
     *
     * @author l_yu 2021/07/28
     * @email l_yu@juling.vip
     */
    public function clearCache(string $key)
    {
        $cache = $this->container->get(CacheInterface::class);
        $key = 'appkey_' . $key;
        return $cache->delete($key);
    }
}