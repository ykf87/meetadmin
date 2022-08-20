<?php
namespace App\Admin\Modals;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Consume;

use Encore\Admin\Widgets\Table;

class Used implements Renderable{
	public function render($key = null){
		// dd($key);
		$headers 	= ['Id', '类型', '开始时间', '时长(秒)', '消耗', '状态'];
		$res 		= Consume::select('id', 'voice', 'start', 'usetime', 'cost', 'status')->where('uid', $key)->orderByDesc('id')->get();
		foreach($res as &$item){
			if($item->start > 0){
				$item->start 	= date('Y-m-d H:i:s', $item->start);
			}

			if(isset(Consume::$voice[$item->voice])){
				$voice 		= $item->voice;
				$item->voice 	= Consume::$voice[$item->voice];
				if(isset(Consume::$voice[$voice])){
					$item->voice 	= '<span class="label label-'.Consume::$voicelabel[$voice].'">' . $item->voice . '</span>';
				}
			}

			$item->status 	= $item->status == 1 ? '扣除正常' : '通话中或非正常挂断';
		}
		$table 		= new Table($headers, $res->toArray());
		echo $table->render();
	}
}