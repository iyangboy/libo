<?php

namespace App\Admin\Controllers;

use \Admin;
use App\Models\Grade;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GradesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员等级设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Grade);

        $grid->column('id', 'ID');
        // $grid->column('slug', __('Slug'));
        $grid->column('name', '名称')->label('success');
        $grid->column('content', '说明');
        $grid->column('loan_limit', '贷款限额')->label('warning');
        $grid->column('interest_rate', '日利率')->label('danger');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

        if (!Admin::user()->can('set-grades')) {
            $grid->tools(function ($tools) {
                // 禁用批量删除按钮
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            // 去掉新建
            $grid->disableCreateButton();

            // 关闭操作
            $grid->disableActions();
        }

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
        $show = new Show(Grade::findOrFail($id));

        $show->field('id', 'ID');
        // $show->field('slug', __('Slug'));
        $show->field('name', '名称');
        $show->field('content', '说明');
        $show->field('loan_limit', '贷款限额');
        $show->field('interest_rate', '日利率');
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
        $form = new Form(new Grade);

        // $form->text('slug', __('Slug'));
        $form->text('name', '名称');
        $form->textarea('content', '说明');
        // $form->decimal('loan_limit', '贷款限额');
        $form->text('loan_limit', '贷款限额');
        // $form->decimal('interest_rate', '日利率');
        $form->text('interest_rate', '日利率');

        return $form;
    }
}
