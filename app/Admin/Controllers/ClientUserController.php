<?php

namespace App\Admin\Controllers;

use App\Models\UCUser;
use App\Models\Country;
use App\Models\Consume;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Admin\Actions\Post\Title;
use App\Admin\Actions\Post\UnTitle;
use App\Admin\Actions\Post\Recharge;
use App\Admin\Actions\Post\Chargebacks;
use App\Admin\Actions\Post\Settlement;
use App\Admin\Modals\Recharges;
use App\Admin\Modals\Used;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Facades\Storage;

class ClientUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UCUser());
        $grid->model()->orderByDesc('id');

        $country       = Country::pluck('name', 'id')->toArray();

        $grid->column('id', __('编号'))->sortable();
        $grid->column('avatar', __('头像'))->display(function($val){
            return $val ? '<img src="https://media.friskymeets.net/'.$val.'" style="max-width:50px;max-height:50px;" />' : '';
        });
        $grid->column('pid', __('推荐人ID'))->sortable()->filter()->editable();
        $grid->column('invite', __('邀请码'))->filter();
        // $table->column('chain', __('Chain'));
        $grid->column('account', __('用户名'))->filter('like')->hide();
        $grid->column('mail', __('邮箱地址'))->filter('like');
        $grid->column('phone', __('电话'))->filter('like');
        $grid->column('sex', __('性别'))->using(UCUser::$sex)->label(UCUser::$sex_label)->filter(UCUser::$sex);
        $grid->column('mailvery', __('邮箱是否认证'))->hide();
        $grid->column('phonevery', __('手机是否认证'))->hide();
        // $table->column('pwd', __('Pwd'));
        $grid->column('nickname', __('昵称'))->filter('like');
        // $table->column('background', __('Background'));
        $grid->column('signature', __('签名'))->hide();
        $grid->column('visits', __('访问量'))->hide()->sortable();
        $grid->column('height', __('身高'))->hide();
        $grid->column('weight', __('体重'))->hide();
        $grid->column('birth', __('生日'))->display(function($val){
            if($val > 0){
                return date('Y-m-d', $val);
            }
            return null;
        })->sortable()->filter('range')->hide();
        $grid->column('realuser', __('实名'))->display(function($val){
            return $val ? '<img src="https://media.friskymeets.net/'.$val.'" style="max-width:50px;max-height:50px;" />' : '';
        })->hide();
        $grid->column('recharge', '充值(美金)')->modal('充值记录', Recharges::class)->sortable();
        // $grid->column('used', __('消费(金币)'))->modal('消费记录', Used::class)->sortable();
        $grid->column('used', __('消费(金币)'))->expand(function ($model) {
            if($this->customer == 1){
                $comments   = $model->useds()->where('id', '>', $model->settlement)->get()->map(function ($comment) {
                    return $comment->only(['connect_id', 'voice', 'usetime', 'cost']);
                });
                $comments   = $comments->toArray();
                $totals     = 0;
                $timers     = 0;
                foreach($comments as &$item){
                    $voice              = $item['voice'];
                    $item['voice']      = Consume::$voice[$item['voice']] ?? null;
                    $item['voice']      = '<span class="label label-'.Consume::$voicelabel[$voice].'">' . $item['voice'] . '</span>';
                    $totals             += $item['cost'];
                    $timers             += $item['usetime'];
                }
                $comments[]             = ['', '', '总时长: ' . $timers, '总消耗: '.$totals];

                return new Table(['对方id', '类型', '待结算时长(秒)', '待结算金币'], $comments); 
            }else{
                return '<center>不是客服号!</center>';
            }
            
        })->sortable();

        $grid->column('balance', __('余额(金币)'))->display(function($val){return intval($val);})->sortable();
        // $table->column('age', __('Age'));
        $grid->column('job', __('工作'))->hide();
        $grid->column('income', __('收入'))->hide();
        $grid->column('emotion', __('情感状态'))->hide();
        $grid->column('constellation', __('星座'))->hide();
        $grid->column('edu', __('教育程度'))->hide();
        $grid->column('temperament', __('性格'))->hide();
        // $table->column('ip', __('Ip'));
        $grid->column('country', __('国家'))->filter($country)->using($country);
        $grid->column('province', __('城市'))->hide();
        // $table->column('city', __('City'));
        // $table->column('singleid', __('Singleid'));
        $grid->column('lang', __('语言'))->hide();
        $grid->column('currency', __('货币'))->hide();
        $grid->column('timezone', __('时区'))->hide();
        $grid->column('platform', __('平台'))->hide();
        // $table->column('md5', __('Md5'));
        $grid->column('private', __('私密账号'))->hide();
        $grid->column('ip', __('ipv4'))->display(function($val){
            return long2ip($val);
        })->filter('like')->hide();

        $states = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ];
        $grid->column('customer', __('客服号'))->switch($states)->hide();
        $grid->column('addtime', __('注册时间'))->sortable()->filter('range')->display(function($val){
            return $val ? date('Y-m-d H:i:s', $val) : null;
        });
        $grid->column('status', __('状态'))->using(UCUser::$status)->label(UCUser::$status_label)->filter(UCUser::$status);


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

            $actions->add(new Title);
            $actions->add(new UnTitle);
            $actions->add(new Recharge);
            $actions->add(new Chargebacks);
            if($actions->getAttribute('customer') == 1){
                // $actions->setResource('<li class="divider"></li>');
                $actions->add(new Settlement);
            }
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
        $show = new Show(UCUser::findOrFail($id));

        // $show->field('id', __('Id'));
        // $show->field('pid', __('Pid'));
        // $show->field('invite', __('Invite'));
        // $show->field('chain', __('Chain'));
        // $show->field('account', __('Account'));
        // $show->field('mail', __('Mail'));
        // $show->field('phone', __('Phone'));
        // $show->field('mailvery', __('Mailvery'));
        // $show->field('phonevery', __('Phonevery'));
        // $show->field('pwd', __('Pwd'));
        // $show->field('nickname', __('Nickname'));
        // $show->field('avatar', __('Avatar'));
        // $show->field('background', __('Background'));
        // $show->field('signature', __('Signature'));
        // $show->field('visits', __('Visits'));
        // $show->field('addtime', __('Addtime'));
        // $show->field('status', __('Status'));
        // $show->field('sex', __('Sex'));
        // $show->field('height', __('Height'));
        // $show->field('weight', __('Weight'));
        // $show->field('birth', __('Birth'));
        // $show->field('age', __('Age'));
        // $show->field('job', __('Job'));
        // $show->field('income', __('Income'));
        // $show->field('emotion', __('Emotion'));
        // $show->field('constellation', __('Constellation'));
        // $show->field('edu', __('Edu'));
        // $show->field('temperament', __('Temperament'));
        // $show->field('ip', __('Ip'));
        // $show->field('country', __('Country'));
        // $show->field('province', __('Province'));
        // $show->field('city', __('City'));
        // $show->field('singleid', __('Singleid'));
        // $show->field('lang', __('Lang'));
        // $show->field('currency', __('Currency'));
        // $show->field('timezone', __('Timezone'));
        // $show->field('platform', __('Platform'));
        // $show->field('md5', __('Md5'));
        // $show->field('private', __('Private'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UCUser());

        $form->number('pid', __('Pid'));
        $form->text('invite', __('Invite'));
        $form->textarea('chain', __('Chain'));
        $form->text('account', __('Account'));
        $form->email('mail', __('Mail'));
        $form->mobile('phone', __('Phone'));
        $form->switch('mailvery', __('Mailvery'));
        $form->switch('phonevery', __('Phonevery'));
        $form->password('pwd', __('Pwd'));
        $form->text('nickname', __('Nickname'));
        $form->image('avatar', __('Avatar'));
        $form->text('background', __('Background'));
        $form->text('signature', __('Signature'));
        $form->number('visits', __('Visits'));
        $form->number('addtime', __('Addtime'));
        $form->switch('status', __('Status'))->default(1);
        $form->switch('sex', __('Sex'));
        $form->switch('height', __('Height'));
        $form->decimal('weight', __('Weight'))->default(0.00);
        $form->number('birth', __('Birth'));
        $form->switch('age', __('Age'));
        $form->text('job', __('Job'));
        $form->switch('income', __('Income'));
        $form->switch('emotion', __('Emotion'));
        $form->switch('constellation', __('Constellation'));
        $form->switch('edu', __('Edu'));
        $form->textarea('temperament', __('Temperament'));
        $form->number('ip', __('Ip'));
        $form->number('country', __('Country'));
        $form->number('province', __('Province'));
        $form->number('city', __('City'));
        $form->switch('singleid', __('Singleid'));
        $form->text('lang', __('Lang'));
        $form->text('currency', __('Currency'));
        $form->text('timezone', __('Timezone'));
        $form->switch('platform', __('Platform'));
        $form->text('md5', __('Md5'));
        $form->switch('private', __('Private'));
        $form->switch('customer', __('Customer'));

        return $form;
    }
}
