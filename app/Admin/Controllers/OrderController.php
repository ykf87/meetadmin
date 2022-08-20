<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\UCUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());
        $grid->model()->orderByDesc('id');

        $grid->column('id', __('充值编号'))->sortable();
        $grid->column('orderid', __('订单编号'))->filter('like');
        $grid->column('mail', __('用户(邮箱)'))->filter('like');
        $grid->column('amount', __('价值(美元)'))->filter('range');
        $grid->column('bi', __('金币'))->range()->sortable();
        $grid->column('addtime', __('发起时间'))->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        })->filter('range', 'datetime')->sortable();
        $grid->column('status', __('状态'))->using(Order::$status)->filter(Order::$status)->label(Order::$statusLabel);
        $grid->column('pay_way', __('付款方式'))->using(Order::$payWay)->filter(Order::$payWay)->label(Order::$payLabel);
        $grid->column('paytime', __('付款时间'))->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        })->filter('range', 'datetime')->sortable();



        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableActions();
        // $grid->actions(function ($actions) {
        //     // 去掉删除
        //     $actions->disableDelete();
        //     // 去掉编辑
        //     $actions->disableEdit();
        //     // 去掉查看
        //     $actions->disableView();
        // });

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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('uid', __('Uid'));
        $show->field('pid', __('Pid'));
        $show->field('orderid', __('Orderid'));
        $show->field('amount', __('Amount'));
        $show->field('bi', __('Bi'));
        $show->field('addtime', __('Addtime'));
        $show->field('status', __('Status'));
        $show->field('pay_way', __('Pay way'));
        $show->field('paytime', __('Paytime'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->number('uid', __('Uid'));
        $form->number('pid', __('Pid'));
        $form->text('orderid', __('Orderid'));
        $form->decimal('amount', __('Amount'));
        $form->number('bi', __('Bi'));
        $form->number('addtime', __('Addtime'));
        $form->switch('status', __('Status'));
        $form->switch('pay_way', __('Pay way'))->default(1);
        $form->number('paytime', __('Paytime'));

        return $form;
    }
}
