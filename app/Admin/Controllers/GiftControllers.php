<?php

namespace App\Admin\Controllers;

use App\Models\Gift;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Illuminate\Support\Facades\Storage;

class GiftControllers extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Gift';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        // dd(Storage::disk('minio')->put('file.jpg', 'fdf'));
        $grid = new Grid(new Gift());

        $states = [
            'on'  => ['value' => 1, 'text' => '正常', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        ];

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('名称'))->editable();
        $grid->column('icon', __('图标'))->image();
        $grid->column('bi', __('币值'))->sortable()->editable();
        $grid->column('sort', __('排序'))->sortable()->editable();
        $grid->column('status', __('状态'))->switch($states);

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
        $show = new Show(Gift::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('icon', __('Icon'));
        $show->field('bi', __('Bi'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new Gift());

        $form->text('name', __('名称'));
        $form->image('icon', __('图标'));
        $form->number('bi', __('币值'))->default(1);
        $form->number('sort', __('排序'));
        $form->switch('status', __('状态'))->default(1);

        return $form;
    }
}
