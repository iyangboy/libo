<?php

namespace App\Admin\Controllers;

use App\Models\UserStatistic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StatisticsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UV-记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserStatistic);

        $grid->column('id', __('Id'));
        $grid->column('day_at', '时间');
        // $grid->column('count', __('Count'));
        $grid->column('uv', 'UV');
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

        $grid->actions(function ($actions) {

            // 去掉删除
            $actions->disableDelete();

            // 去掉编辑
            $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
        });

        // 全部关闭
        $grid->disableActions();
        // 去掉新建
        $grid->disableCreateButton();
        // 去掉导出
        $grid->disableExport();
        // 去掉筛选
        $grid->disableFilter();
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
        $show = new Show(UserStatistic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('day_at', __('Day at'));
        $show->field('count', __('Count'));
        $show->field('uv', __('Uv'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserStatistic);

        $form->date('day_at', __('Day at'))->default(date('Y-m-d'));
        $form->number('count', __('Count'));
        $form->number('uv', __('Uv'));

        return $form;
    }
}
