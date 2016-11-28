<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 9/20/16
 * Time: 10:25 PM
 */

namespace App\Http\Controllers\News;


use App\Http\Controllers\Controller;
use App\News;

class NewsController extends Controller
{

	/**
	 * Return all news active
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllNews()
	{
		$news = News::all();
		return $news;
	}


}