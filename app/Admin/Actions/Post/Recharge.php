<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Models\Order;

class Recharge extends RowAction{
    public $name = '充值';

    public function handle(Model $model, Request $request){
        $bi     = (int)$request->input('bi');
        if($bi <= 0){
            return $this->response()->error('代币数量不能小于0');
        }

        $rs     = Order::adminAdd($model, $bi);
        if($rs === true){
            return $this->response()->success(sprintf('成功充值 %d 到 %s', $bi, $model->mail));
        }
        return $this->response()->error($rs);
    }

    public function form(Model $model){
        $this->text('bi', $model->mail . ':请输入充值代币数量!');
    }
}