<?php

namespace App\Admin\Controllers;

use App\Models\Settlement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SettlementControllers extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '佣金结算记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Settlement());

        $grid->column('id', __('ID'));
        $grid->column('aid', __('管理员ID'))->filter();
        $grid->column('uid', __('客服号'))->filter();
        $grid->column('timers', __('结算时长'))->filter('range')->sortable();
        $grid->column('costs', __('结算费用'))->filter('range')->sortable();
        $grid->column('addtime', __('结算时间'))->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        });
        // $grid->column('lastid', __('Lastid'));
        $grid->column('sids', __('消费记录'))->display(function($val){
            return $val;
        })->hide();

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
        $show = new Show(Settlement::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('aid', __('Aid'));
        $show->field('uid', __('Uid'));
        $show->field('timers', __('Timers'));
        $show->field('costs', __('Costs'));
        $show->field('addtime', __('Addtime'));
        $show->field('lastid', __('Lastid'));
        $show->field('sids', __('Sids'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Settlement());

        $form->number('aid', __('Aid'));
        $form->number('uid', __('Uid'));
        $form->number('timers', __('Timers'));
        $form->number('costs', __('Costs'));
        $form->number('addtime', __('Addtime'));
        $form->number('lastid', __('Lastid'));
        $form->textarea('sids', __('Sids'));

        return $form;
    }
}
