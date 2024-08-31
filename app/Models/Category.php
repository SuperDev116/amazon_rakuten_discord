<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'access_key',
        'secret_key',
        'partner_tag',
        'am_fall_pro',
        'am_target_price',
        'am_web_hook',
        'affiliate_id',
        'application_id',
        'ra_fall_pro',
        'ra_target_price',
        'ra_web_hook',
        'yahoo_id',
        'ya_fall_pro',
        'ya_target_price',
        'ya_web_hook',
        'len',
        'file_name',
        'reg_num',
        'trk_num',
        'is_reg',
        'stop',
        'round',
    ];

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function items() {
        return $this->hasMany(
            Item::class,
        );
    }
}
