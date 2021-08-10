<?php

namespace App\Service\Notice;

use App\Extend\SmsAli\SendSms;
use Hyperf\Utils\ApplicationContext;
use App\Model\Main\Sms;
use Hyperf\Di\Annotation\Inject;
class NoticeSmsService
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    const EXPIRE_TIME = 300;
    //短信过期时间300秒
    const RESENT_TIME = 60;
    //短信重发限制60秒
    public $accessKeyId = '';
    public $accessKeySecret = '';
    public $signName = '';
    /**
     * @Inject()
     * @var Sms
     */
    private $smsModel;
    public function __construct()
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->accessKeyId = env('AliSms.ACCESS_KEY_ID', '');
        $this->accessKeySecret = env('AliSms.ACCESS_KEY_SECRET', '');
        $this->signName = env('AliSms.SIGN_NAME', '');
    }
    /**
     * @param $tel
     * @return bool
     * @note   手机号码限制
     * @author   yangWanZhang   2021/7/20
     * @email  wz_yang@juling.vip
     * @modifier yangWanZhang 2021/7/20
     */
    public function phoneWhiteListLimit($tel)
    {
        $telThree = substr($tel, 0, 3);
        if ($telThree == 177 || $telThree == 171 || $telThree == 170) {
            $redisKey = 'life:phone:white:list:config';
            $container = ApplicationContext::getContainer();
            $redis = $container->get(\Hyperf\Redis\Redis::class);
            $redisValue = $redis->lRange($redisKey, 0, -1);
            $telYn = in_array($tel, $redisValue);
            if (empty($telYn)) {
                return false;
            }
        }
        return true;
    }
    /**
     * @param $tel
     * @param $type
     * @return
     * @note 发送手机验证码
     * @author   yangWanZhang   2021/7/20
     * @email  wz_yang@juling.vip
     * @modifier yangWanZhang 2021/7/20
     */
    public function sendSms($tel, $type)
    {
        if (!preg_match("/^1\\d{10}\$/", $tel)) {
            return false;
        }
        $telCode = rand(100000, 999999);
        if ($sms = $this->smsModel->getSms($tel, $type)) {
            if ($sms['created_at'] + self::RESENT_TIME > time()) {
                return false;
            } else {
                $resCode = $this->smsModel->where(['tel' => $tel, 'type' => $type])->update(['code' => $telCode, 'expire_time' => time() + self::EXPIRE_TIME]);
            }
        } else {
            $resCode = $this->smsModel->insert(['tel' => $tel, 'type' => $type, 'code' => $telCode, 'created_at' => time(), 'expire_time' => time() + self::EXPIRE_TIME]);
        }
        if ($resCode) {
            $sendSms = new SendSms();
            $sendRes = $sendSms->sendSms($tel, $type, $telCode, $this->accessKeyId, $this->accessKeySecret, $this->signName);
            if ($sendRes->Code == 'OK') {
                return true;
            }
        }
        return false;
    }
}