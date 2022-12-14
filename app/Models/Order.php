<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Encore\Admin\Facades\Admin;
use App\Models\Consume;

class Order extends Model{
	use HasFactory;
	protected $connection 	= 'ucenter';
	public $timestamps = false;

	public static $payWay 	= [
		1	=> '贝宝',
		2	=> '苹果',
		3	=> '后台',
	];
	public static $payLabel	= [
		1 	=> 'danger',
		2	=> 'warning',
		3	=> 'primary',
	];
	public static $status 	= [
		-2		=> '待定状态',
		-1		=> '无效订单',
		0		=> '待支付',
		1 		=> '支付成功',
		2		=> '待付款',
		3		=> '待验证'
	];
	public static $statusLabel 	= [
		-2		=> 'default',
		-1		=> 'default',
		0		=> 'info',
		1 		=> 'success',
		2		=> 'warning',
		3		=> 'danger'
	];

	public static function adminAdd(Model $user, int $bi){
		if($bi <= 0){
			return '充值数量不能为0';
		}
		$order 			= new self;
		$order->uid 	= $user->id;
		$order->pid 	= 1;
		$order->orderid	= Admin::user()->id;
		$order->amount 	= 0;
		$order->bi 		= $bi;
		$order->addtime	= time();
		$order->status 	= 1;
		$order->pay_way	= 3;
		$order->paytime = $order->addtime;
		$order->mail 	= $user->mail;

		if($order->save()){
			$user->recharge 	= self::where('uid', $user->id)->sum('amount');
			$user->used 		= Consume::where('uid', $user->id)->sum('cost');
			$user->balance 		= self::where('uid', $user->id)->sum('bi') - $user->used;
			$user->save();
			return true;
		}else{
			return '充值失败!';
		}
	}
}
