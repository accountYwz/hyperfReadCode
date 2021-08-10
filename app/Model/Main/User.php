<?php

declare (strict_types=1);
namespace App\Model\Main;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $account 
 * @property int $status 
 * @property string $create_time 
 * @property string $update_time 
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
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
    protected $casts = ['id' => 'integer', 'account' => 'integer', 'status' => 'integer'];
}