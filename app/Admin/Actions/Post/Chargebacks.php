<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Models\Consume;

class Chargebacks extends RowAction{
    public $name = '扣费';

    public function handle(Model $model, Request $request){
        $bi     = (int)$request->input('bi');
        if($bi <= 0){
            return $this->response()->error('代币数量不能小于0');
        }

        $rs     = Consume::adminAdd($model, $bi);
        if($rs === true){
            return $this->response()->success(sprintf('成功扣除 %s 金币 %d', $model->mail, $bi));
        }
        return $this->response()->error($rs);
    }

    public function form(Model $model){
        $this->text('bi', $model->mail . ':请输入扣除代币数量!');
    }
}