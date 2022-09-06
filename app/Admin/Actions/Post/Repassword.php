<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Repassword extends RowAction{
    public $name = '修改密码';

    public function handle(Model $model, Request $request){
        $pwd    = trim($request->input('password', ''));
        if(!$pwd){
            return $this->response()->error('密码不能为空!');
        }
        $model->pwd         = password_hash($pwd, PASSWORD_DEFAULT);
        $model->singleid    += 1;
        if($model->save()){
            return $this->response()->success('密码修改成功!')->refresh();
        }
        return $this->response()->error('密码修改失败,请联系开发人员!');
    }
    public function form(Model $model){
        $this->password('password', $model->mail . ' 新密码...');
    }
}