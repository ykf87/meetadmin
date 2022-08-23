<?php

namespace App\Admin\Controllers;

use App\Models\Consume;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConsumeControllers extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '消费记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Consume());
        $grid->model()->orderByDesc('id');

        $grid->column('id', __('ID'));
        $grid->column('uid', __('用户ID'))->filter();
        $grid->column('connect_id', __('对方ID'))->filter();
        $grid->column('voice', __('类型'))->using(Consume::$voice)->label(Consume::$voicelabel)->filter(Consume::$voice);;
        $grid->column('start', __('开始时间'))->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        })->filter('range', 'datetime');
        $grid->column('uptime', __('更新时间'))->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        })->filter('range', 'datetime');
        $grid->column('end', __('结束时间'))->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        })->filter('range', 'datetime');
        $grid->column('usetime', __('总耗时(秒)'))->sortable();
        $grid->column('seccost', __('单次费用'));
        $grid->column('cost', __('总费用'))->sortable();
        // $grid->column('status', __('Status'));

        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });

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
        $show = new Show(Consume::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('uid', __('Uid'));
        $show->field('connect_id', __('Connect id'));
        $show->field('voice', __('Voice'));
        $show->field('start', __('Start'));
        $show->field('uptime', __('Uptime'));
        $show->field('end', __('End'));
        $show->field('usetime', __('Usetime'));
        $show->field('seccost', __('Seccost'));
        $show->field('cost', __('Cost'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Consume());

        $form->number('uid', __('Uid'));
        $form->text('connect_id', __('Connect id'));
        $form->switch('voice', __('Voice'));
        $form->number('start', __('Start'));
        $form->number('uptime', __('Uptime'));
        $form->number('end', __('End'));
        $form->number('usetime', __('Usetime'))->default(1);
        $form->number('seccost', __('Seccost'));
        $form->number('cost', __('Cost'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
