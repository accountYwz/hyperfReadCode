<?php

declare (strict_types=1);
namespace App\Model\Manager;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $key 
 * @property string $secret 
 * @property int $device_type 
 * @property int $is_del 
 * @property string $create_time 
 * @property string $update_time 
 */
class Appkey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appkey';
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'manager';
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
    protected $casts = ['id' => 'integer', 'device_type' => 'integer', 'is_del' => 'integer'];
}