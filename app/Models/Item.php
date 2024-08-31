<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use HasFactory;

	protected $table = 'items';

	protected $fillable = [
		'user_id',
		'category_id',
		'asin',
		'name',
		'img_url',
		'am_price',
		'am_item_url',
		'register_price',
		'target_price',
		'jan',
		'ya_price',
		'ya_item_url',
		'ra_price',
		'ra_item_url',
		'status',
		'am_notified',
		'ya_notified',
		'ra_notified',
	];

	public function category() {
		return $this->belongsTo(
			User::class,
			'category_id'
		);
	}

	public function user() {
		return $this->belongsTo(
			User::class,
			'user_id'
		);
	}
}
