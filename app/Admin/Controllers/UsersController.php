<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->model()->with(['source']);

        // 创建一个列名为 ID 的列，内容是用户的 id 字段
        $grid->id('ID');
        // 创建一个列名为 用户名 的列，内容是用户的 name 字段。下面的 email() 和 created_at() 同理
        $grid->name('用户名');
        $grid->column('phone', '手机号');
        $grid->column('source.name', '来源')->label('success');
        // $grid->email('邮箱');
        // $grid->email_verified_at('已验证邮箱')->display(function ($value) {
        //     return $value ? '是' : '否';
        // });
        // $grid->column('password', __('Password'));
        // $grid->column('weixin_openid', __('Weixin openid'));
        // $grid->column('weixin_unionid', __('Weixin unionid'));
        // $grid->column('remember_token', __('Remember token'));
        $grid->created_at('注册时间');
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('avatar', __('Avatar'));
        // $grid->column('introduction', __('Introduction'));
        // $grid->column('notification_count', __('Notification count'));
        // $grid->column('last_actived_at', __('Last actived at'));
        // $grid->column('registration_id', __('Registration id'));

        // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户
        $grid->disableCreateButton();
        // 同时在每一行也不显示 `编辑` 按钮
        $grid->disableActions();
        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('weixin_openid', __('Weixin openid'));
        $show->field('weixin_unionid', __('Weixin unionid'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('avatar', __('Avatar'));
        $show->field('introduction', __('Introduction'));
        $show->field('notification_count', __('Notification count'));
        $show->field('last_actived_at', __('Last actived at'));
        $show->field('registration_id', __('Registration id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', __('Name'));
        $form->mobile('phone', __('Phone'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('weixin_openid', __('Weixin openid'));
        $form->text('weixin_unionid', __('Weixin unionid'));
        $form->text('remember_token', __('Remember token'));
        $form->image('avatar', __('Avatar'));
        $form->text('introduction', __('Introduction'));
        $form->number('notification_count', __('Notification count'));
        $form->datetime('last_actived_at', __('Last actived at'))->default(date('Y-m-d H:i:s'));
        $form->text('registration_id', __('Registration id'));

        return $form;
    }

    // 获取后台用户
    public function selectAdminUser()
    {
        $adminUsers = AdminUser::get();
        $select = [];
        //dd($series->toArray());
        foreach ($adminUsers as $key => $value) {
            $select[$key]['id'] = $value->id;
            $select[$key]['text'] = $value->name ?? '';
        }
        return $select;
    }
}
