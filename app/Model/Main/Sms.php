<?php

declare (strict_types=1);
namespace App\Model\Main;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $tel 手机号
 * @property int $type 类型，1-注册 2-修改密码 3-手机登录 4-修改密保手机 5-身份校验 6-更换绑定手机
 * @property string $code 验证码
 * @property int $expire_time 过期时间
 * @property int $created_at 添加时间
 * @property string $updated_at 更新时间
 */
class Sms extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'expire_time' => 'integer', 'created_at' => 'integer'];

    /**
     * @param $tel
     * @param $type
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Model\Model|null
     * @note  查询短信
     * @author   yangWanZhang   2021/7/20
     * @email  wz_yang@juling.vip
     * @modifier yangWanZhang 2021/7/20
     */
    public function getSms($tel,$type){
        return $this->where(['tel'=>$tel,'type'=>$type])->first();
    }
}