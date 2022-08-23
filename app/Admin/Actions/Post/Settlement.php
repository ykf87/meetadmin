<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settlement as SL;
use App\Models\Consume;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;

class Settlement extends RowAction{
    public $name = '结算';

    public function dialog(){
        $this->confirm('结算不可逆,确定结算?');
    }
    public function handle(Model $model){
        if($model->customer != 1){
            return $this->response()->error('当前不是客服号,无法进行结算!');
        }

        $last   = SL::select('lastid')->where('uid', $model->id)->orderByDesc('id')->first();
        $lastID = 0;
        if($last){
            $lastID     = $last->lastid;
        }
        if($lastID != $model->settlement){
            return $this->response()->error('结算记录有误,请联系开发人员核对!');
        }


        $history        = Consume::where('uid', $model->id)->where('id', '>', $lastID)->get();
        if(count($history) < 1){
            return $this->response()->error('暂无结算记录!');
        }


        $newLastId      = $lastID;
        $addIds         = [];
        $timers         = 0;
        $costs          = 0;
        foreach($history as $item){
            $timers     += $item->usetime;
            $costs      += $item->cost;
            $newLastId  = $item->id;
            $addIds[]   = $item->id;
        }

        $sls            = new SL;
        $sls->addtime   = time();
        $sls->aid       = Admin::user()->id;
        $sls->uid       = $model->id;
        $sls->timers    = $timers;
        $sls->costs     = $costs;
        $sls->lastid    = $newLastId;
        $sls->sids      = implode(',', $addIds);

        $oldLastId          = $model->settlement;
        $model->settlement  = $newLastId;

        if($model->save()){
            try {
                $sls->save();
                return $this->response()->success('结算成功,总时长: ' . $timers . ', 总金币: ' . $costs)->refresh();
            } catch (\Exception $e) {
                $model->settlement  = $oldLastId;
                $model->save();
            }
        }


        return $this->response()->error('系统错误,请联系开发人员!');
    }

}