<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        // 只展示已支付的订单，并且默认按支付时间倒序排序
        $grid->model()->with(['user', 'product']);

        $grid->column('id', 'ID');
        $grid->column('no', '订单流水号');
        $grid->column('user.name', '用户');
        $grid->column('product.title', '商品');
        $grid->column('loan_amount', '借款金额');
        $grid->column('price', '服务费');
        // $grid->column('product_by_stage_id', __('Product by stage id'));
        $grid->column('by_stage', '分期数');
        $grid->column('remark', '备注');
        $grid->column('paid_at', '支付时间');
        $grid->column('payment_method', '支付方式');
        $grid->column('payment_no', '支付编号');
        // $grid->column('refund_status', '退款状态');
        // $grid->column('refund_no', '退款编号');
        $grid->column('closed', '是否关闭');
        // $grid->column('reviewed', __('Reviewed'));
        $grid->column('ship_status', '订单状态');
        // $grid->column('ship_data', __('Ship data'));
        // $grid->column('extra', __('Extra'));
        $grid->column('created_at', '创建时间');
        // $grid->column('updated_at', '更新时间');

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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('no', '订单流水号');
        $show->field('user.name', '用户');
        $show->field('product.title', '商品');
        $show->field('loan_amount', '借款金额');
        $show->field('price', '服务费');
        // $show->field('product_by_stage_id', __('Product by stage id'));
        $show->field('by_stage', '分期数');
        $show->field('remark', '备注');
        $show->field('paid_at', '支付时间');
        $show->field('payment_method', '支付方式');
        $show->field('payment_no', '支付编号');
        // $show->field('refund_status', '退款状态');
        // $show->field('refund_no', '退款编号');
        $show->field('closed', '是否关闭');
        // $show->field('reviewed', __('Reviewed'));
        $show->field('ship_status', '订单状态');
        // $show->field('ship_data', __('Ship data'));
        // $show->field('extra', __('Extra'));
        $show->field('created_at', '创建时间');
        // $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->number('user_id', __('User id'));
        $form->number('product_id', __('Product id'));
        $form->text('no', '订单流水号');
        $form->decimal('loan_amount', '借款金额');
        $form->decimal('price', '服务费');
        // $form->number('product_by_stage_id', __('Product by stage id'));
        $form->number('by_stage', '分期数')->default(1);
        $form->textarea('remark', '备注');
        $form->datetime('paid_at', '支付时间')->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', '支付方式');
        $form->text('payment_no', '支付编号');
        // $form->text('refund_status', '退款状态')->default('pending');
        $form->text('refund_no', '退款编号');
        $form->switch('closed', '是否关闭');
        $form->text('ship_status', '订单状态')->default('pending');

        return $form;
    }
}
