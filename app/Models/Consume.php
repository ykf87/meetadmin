<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Facades\Admin;

class Consume extends Model{
	use HasFactory;
	protected $connection 	= 'ucenter';
	public $timestamps 		= false;
	public static $voice	= [
		1	=> '语音',
		2	=> '视频',
		3	=> '后台',
	];
	public static $voicelabel	= [
		1	=> 'warning',
		2	=> 'success',
		3	=> 'default',
	];

	public static function adminAdd(Model $user, int $bi){
		if($bi <= 0){
			return '扣除数量不能为0';
		}
		$order 				= new self;
		$order->uid 		= $user->id;
		$order->connect_id	= Admin::user()->id;
		$order->voice 		= 1;
		$order->start 		= time();
		$order->uptime		= $order->start;
		$order->end			= $order->start;
		$order->usetime 	= 0;
		$order->seccost		= 0;
		$order->cost 		= $bi;
		$order->status 		= 1;

		if($order->save()){
			return true;
		}else{
			return '扣费失败!';
		}
	}
}
