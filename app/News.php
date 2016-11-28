<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

	const SHOPPING 	= 	0;
	const HEALTH	=	1;
	const EVENT		=	2;
	const TIPS		=	3;

	const STATUS_ENABLE = 1;
	const STATUS_DISABLED = 0;

	/**
	 * Array with icons url
	 * @var array
	 */
	public static $typesIcons = [
		News::SHOPPING => 'shop-icon.png',
		News::HEALTH => 'health-icon.png',
		News::EVENT => 'events-icon.png',
		News::TIPS => 'tips-icon.png'
	];
	/**
	 * Array with the news names
	 * @var array
	 */
	public static $typesNames = [
		News::SHOPPING => 'Shopping',
		News::HEALTH => 'Health',
		News::EVENT => 'Event',
		News::TIPS => 'Tip'
	];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title','image','thumb','content','type','date','latitude','longitude','icon','status'
	];

}
