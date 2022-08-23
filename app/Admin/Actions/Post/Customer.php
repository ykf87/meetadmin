<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Customer extends RowAction{
    public $name = '设置客服';

    public function dialog(){
        $this->confirm('确定将用户设置为客服?');
    }
    public function handle(Model $model){
        if($model->customer == 1){
            return $this->response()->error('该账号已经是客服');
        }
        $model->status      = 1;
        $model->singleid    = 0;
        if($model->save()){
            return $this->response()->success('账号成功解封!')->refresh();
        }
        return $this->response()->error('系统错误,请联系开发人员!');
    }

}