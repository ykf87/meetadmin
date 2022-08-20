<?php
namespace App\Admin\Modals;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Order;

use Encore\Admin\Widgets\Table;

class Recharges implements Renderable{
	public function render($key = null){
		// dd($key);
		$headers 	= ['Id', '订单id', '实付金额', '金币数量', '发生时间', '状态', '支付方式'];
		$res 		= Order::select('id', 'orderid', 'amount', 'bi', 'addtime', 'status', 'pay_way')->where('uid', $key)->orderByDesc('id')->get();
		foreach($res as &$item){
			if($item->addtime > 0){
				$item->addtime 	= date('Y-m-d H:i:s', $item->addtime);
			}
			if(isset(Order::$status[$item->status])){
				$status 		= $item->status;
				$item->status 	= Order::$status[$item->status];
				if(isset(Order::$statusLabel[$status])){
					$item->status 	= '<span class="label label-'.Order::$statusLabel[$status].'">' . $item->status . '</span>';
				}
			}
			if(isset(Order::$payWay[$item->pay_way])){
				$payway 		= $item->pay_way;
				$item->pay_way 	= Order::$payWay[$item->pay_way];
				if(isset(Order::$payLabel[$payway])){
					$item->pay_way 	= '<span class="label label-'.Order::$payLabel[$payway].'">' . $item->pay_way . '</span>';
				}
			}
		}
		$table 		= new Table($headers, $res->toArray());
		echo $table->render();
	}
}