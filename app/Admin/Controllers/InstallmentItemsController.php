<?php

namespace App\Admin\Controllers;

use App\Models\InstallmentItem;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InstallmentItemsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分期详细';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InstallmentItem);

        $grid->column('id', 'ID');
        $grid->column('installment_id', '分期 ID');
        $grid->column('sequence', '还款顺序编号');
        $grid->column('base', '当期本金');
        $grid->column('fee', '当期手续费');
        $grid->column('fine', '当期逾期费');
        $grid->column('due_date', '还款截止日期');
        $grid->column('paid_at', '还款日期');
        $grid->column('payment_method', '还款支付方式');
        $grid->column('payment_no', '还款支付平台订单号');
        // $grid->column('refund_status', '退款状态');
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
        $show = new Show(InstallmentItem::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('installment_id', '分期 ID');
        $show->field('sequence', '还款顺序编号');
        $show->field('base', '当期本金');
        $show->field('fee', '当期手续费');
        $show->field('fine', '当期逾期费');
        $show->field('due_date', '还款截止日期');
        $show->field('paid_at', '还款日期');
        $show->field('payment_method', '还款支付方式');
        $show->field('payment_no', '还款支付平台订单号');
        // $show->field('refund_status', '退款状态');
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
        $form = new Form(new InstallmentItem);

        $form->number('installment_id','分期 ID');
        $form->number('sequence', '还款顺序编号');
        $form->decimal('base','当期本金');
        $form->decimal('fee', '当期手续费');
        $form->decimal('fine', '当期逾期费');
        $form->datetime('due_date', '还款截止日期')->default(date('Y-m-d H:i:s'));
        $form->datetime('paid_at', '还款日期');
        $form->text('payment_method', '还款支付方式');
        $form->text('payment_no', '还款支付平台订单号');
        // $form->text('refund_status', '退款状态')->default('pending');

        return $form;
    }
}
