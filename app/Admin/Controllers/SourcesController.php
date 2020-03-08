<?php

namespace App\Admin\Controllers;

use App\Models\Source;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SourcesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '来源';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Source);

        $grid->column('id', 'iD');
        $grid->column('name', '名称');
        $grid->column('slug', '标记');
        $grid->column('admin_user_id', '关联管理员');
        $grid->column('remark', '备注');

        $grid->column('user_count', '来源总数');

        $grid->column('user_count_decrement', '来源扣除数');
        $grid->column('user_count_decrement_base', '扣除基数');
        $grid->column('user_count_real', '真实总数');

        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

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
        $show = new Show(Source::findOrFail($id));

        $show->field('id', 'ID');

        $show->field('name', '名称');
        $show->field('slug', '标记');
        $show->field('admin_user_id', '关联管理员');
        $show->field('remark', '备注');

        $show->field('user_count', '来源总数');

        $show->field('user_count_decrement', '来源扣除数');
        $show->field('user_count_decrement_base', '扣除基数');
        $show->field('user_count_real', '真实总数');

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
        $form = new Form(new Source);

        $form->text('name', '名称');
        $form->text('slug', '标记');
        $form->number('admin_user_id', '关联管理员');
        $form->text('remark', '备注');
        // $form->number('user_count', '来源总数');
        // $form->number('user_count_decrement', '来源扣除数');
        $form->number('user_count_decrement_base', '扣除基数');
        // $form->number('user_count_real', '真实总数');

        return $form;
    }
}
