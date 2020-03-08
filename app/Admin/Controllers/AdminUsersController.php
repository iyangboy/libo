<?php

namespace App\Admin\Controllers;

use \Admin;
use App\Models\AdminUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AdminUsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '后台操作人员';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminUser);

        $grid->column('id', 'ID');
        $grid->column('username', '用户名称');
        // $grid->column('password', __('Password'));
        $grid->column('name', '昵称');
        $grid->column('avatar', '头像');
        // $grid->column('remember_token', __('Remember token'));

        $grid->column('source_count', '来源数');

        if (Admin::user()->can('set-sources')) {
            $grid->column('source_real_count', '来源实际数');
        }

        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户
        $grid->disableCreateButton();
        // 同时在每一行也不显示 `编辑` 按钮
        $grid->disableActions();
        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

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
        $show = new Show(AdminUser::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', '用户名称');
        // $show->field('password', __('Password'));
        $show->field('name', '昵称');
        $show->field('avatar', '头像');
        // $show->field('remember_token', __('Remember token'));

        $show->field('source_count', '来源数');

        if (Admin::user()->can('set-sources')) {
            $show->field('source_real_count', '来源实际数');
        }
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AdminUser);

        $form->text('username', '登录名称');
        // $form->password('password', '密码');
        $form->text('name', '昵称');
        $form->image('avatar', '头像');
        // $form->text('remember_token', __('Remember token'));
        // $form->number('source_count', __('Source count'));
        // $form->number('source_real_count', __('Source real count'));

        return $form;
    }
}
