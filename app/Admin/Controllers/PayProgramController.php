<?php

namespace App\Admin\Controllers;

use App\Models\PayProgram;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PayProgramController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PayProgram';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PayProgram());

        $grid->column('id', __('ID'));
        $grid->column('price', __('价值(美元)'))->editable();
        $grid->column('bi', __('获得金币'))->editable();
        $grid->column('remark', __('备注'))->editable();
        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        ];
        $grid->column('status', __('状态'))->switch($states);
        // $grid->column('used', __('Used'));
        // $grid->column('pin', __('Pin'));
        // $grid->column('pin_time', __('Pin time'));
        // $grid->column('appleid', __('Appleid'));

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
        $show = new Show(PayProgram::findOrFail($id));

        // $show->field('id', __('Id'));
        // $show->field('price', __('Price'));
        // $show->field('bi', __('Bi'));
        // $show->field('remark', __('Remark'));
        // $show->field('status', __('Status'));
        // $show->field('used', __('Used'));
        // $show->field('pin', __('Pin'));
        // $show->field('pin_time', __('Pin time'));
        // $show->field('appleid', __('Appleid'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PayProgram());

        $form->decimal('price', __('价值'));
        $form->number('bi', __('获得金币'));
        $form->text('remark', __('备注'));
        $form->switch('status', __('状态'))->default(1);
        // $form->number('used', __('Used'));
        // $form->switch('pin', __('Pin'));
        // $form->number('pin_time', __('Pin time'));
        // $form->text('appleid', __('Appleid'));

        return $form;
    }
}
