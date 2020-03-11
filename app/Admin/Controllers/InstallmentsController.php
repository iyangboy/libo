<?php

namespace App\Admin\Controllers;

use App\Models\Installment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InstallmentsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分期';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Installment);

        $grid->column('id', 'ID');
        $grid->column('no', '编号');
        $grid->column('user_id', '用户');
        $grid->column('order_id', '订单');
        $grid->column('total_amount', '贷款金额');
        $grid->column('count', '分期数');
        $grid->column('fee_rate', '手续费率');
        $grid->column('fine_rate', '逾期费率');
        $grid->column('status', '状态');
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
        $show = new Show(Installment::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('no', '编号');
        $show->field('user_id', '用户');
        $show->field('order_id', '订单');
        $show->field('total_amount', '贷款金额');
        $show->field('count', '分期数');
        $show->field('fee_rate', '手续费率');
        $show->field('fine_rate', '逾期费率');
        $show->field('status', '状态');
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
        $form = new Form(new Installment);

        $form->text('no', '编号');
        $form->number('user_id', '用户');
        $form->number('order_id', '订单');
        $form->decimal('total_amount', '贷款金额');
        $form->number('count', '分期数');
        $form->decimal('fee_rate', '手续费率');
        $form->decimal('fine_rate', '逾期费率');
        $form->text('status', '状态')->default('pending');

        return $form;
    }
}
