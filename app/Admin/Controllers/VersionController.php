<?php

namespace App\Admin\Controllers;

use App\Models\Version;
use App\Models\Platform;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VersionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '版本管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Version());
        $grid->model()->orderByDesc('id');

        $grid->column('id', __('ID'));
        $grid->column('version_str', __('版本号'))->filter('like');
        $grid->column('version_code', __('版本编号'))->sortable();
        $grid->column('remark', __('版本更新内容'))->display(function($val){
            return '<a href="javascript:;" title="'.$val.'">' . mb_substr($val, 0, 10, 'utf-8') . '</a>';
        })->filter('like');
        $grid->column('url', __('更新地址'))->display(function($val){
            return '<a href="'.$val.'" target="_blank">打开</a>';
        })->qrcode();
        $states = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ];
        $grid->column('must', __('强制升级'))->switch($states)->filter([0 => '否', 1 => '是']);
        $grid->column('canpay', __('启用支付'))->switch($states)->filter([0 => '否', 1 => '是']);
        $grid->column('platform', __('平台'))->using(Platform::list())->filter(Platform::list());

        $grid->column('uptime', __('期望更新时间'))->editable('datetime')->sortable();
        $grid->column('addtime', __('添加时间'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Version::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('version_str', __('版本号'));
        $show->field('version_code', __('版本编号'));
        $show->field('remark', __('版本更新内容'));
        $show->field('url', __('更新地址'));
        $show->field('uptime', __('期望更新时间'));
        $show->field('addtime', __('添加时间'));
        $show->field('must', __('强制升级'));
        $show->field('canpay', __('启用支付'));
        $show->field('platform', __('平台'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Version());

        $form->text('version_str', __('版本号'));
        $form->number('version_code', __('版本编号'));
        $form->radio('platform', __('平台'))->options(Platform::list())->default('1');
        $form->textarea('remark', __('版本更新内容'));
        $form->url('url', __('更新地址'));
        $form->datetime('uptime', __('期望更新时间'));
        $form->hidden('addtime', __('添加时间'))->default(time());
        $form->switch('must', __('强制升级'))->default(0);
        $form->switch('canpay', __('启用支付'))->default(1);
        $form->saving(function (Form $form) {
            // if($form->isCreating()){
            //     $form->addtime  = time();
            // }
            // dd($form);
        });
        return $form;
    }
}
