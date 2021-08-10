<?php

declare(strict_types=1);

namespace App\Model\Main;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $logo
 * @property string $introduce
 * @property string $current_version
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AboutUs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about_us';

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
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
