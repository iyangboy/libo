<?php

namespace App\Admin\Controllers;

use App\Models\CreditLineOrder;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CreditLineOrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '授信订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CreditLineOrder);

        $grid->model()->with(['user', 'product']);

        $grid->column('id', '订单');
        $grid->column('no', '下单编号');
        // $grid->column('type', '类型');
        $grid->column('user.phone', '用户');
        $grid->column('product.title', '授信类型');
        // $grid->column('address', __('Address'));
        $grid->column('total_amount', '价格');
        // $grid->column('remark', __('Remark'));
        $grid->column('paid_at', '支付时间');
        $grid->column('payment_method', '支付方式')->display(function ($value) {
            $text = '';
            switch ($value) {
                case 'ali_pay':
                    $text = '<span class="label label-warning">支付宝</span>';
                    break;
                case 'wechat_pay':
                    $text = '<span class="label label-success">微信</span>';
                    break;
            }
            return $text;
        });
        $grid->column('payment_no', '支付编号');
        // $grid->column('refund_status', '退款状态');
        // $grid->column('refund_no', '退款编号');
        $grid->column('closed', '是否关闭');
        $grid->column('reviewed', '订单是否已评价');
        $grid->column('ship_status', '订单状态')->display(function ($value) {
            $text = '';
            switch ($value) {
                case 'pending':
                    $text = '<span class="label label-warning">已下单</span>';
                    break;
                case 'delivered':
                    $text = '<span class="label label-success">已支付</span>';
                    break;
            }
            return $text;
        });
        // $grid->column('ship_data', __('Ship data'));
        // $grid->column('extra', __('Extra'));
        $grid->column('created_at', '下单时间');
        $grid->column('updated_at', '更新时间');

        if (!\Admin::user()->can('admin-set-credit-line-orders')) {
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
        $show = new Show(CreditLineOrder::findOrFail($id));

        $show->field('id', '订单');
        $show->field('no', '下单编号');
        // $show->field('type', '类型');
        $show->field('user_id', '用户');
        $show->field('product_id', '授信类型');
        // $show->field('address', __('Address'));
        $show->field('total_amount', '价格');
        // $show->field('remark', __('Remark'));
        $show->field('paid_at', '支付时间');
        $show->field('payment_method', '支付方式');
        $show->field('payment_no', '支付编号');
        $show->field('refund_status', '退款状态');
        $show->field('refund_no', '退款编号');
        $show->field('closed', '是否关闭');
        $show->field('reviewed', '订单是否已评价');
        $show->field('ship_status', '订单状态');
        // $show->field('ship_data', __('Ship data'));
        // $show->field('extra', __('Extra'));
        $show->field('created_at', '下单时间');
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
        $form = new Form(new CreditLineOrder);

        // $form->text('no', __('No'));
        // $form->text('type', __('Type'));
        $form->number('user_id', '用户');
        $form->number('product_id', '授信类型');
        // $form->textarea('address', __('Address'));
        $form->decimal('total_amount', '价格');
        $form->textarea('remark', __('Remark'));
        $form->datetime('paid_at', '支付时间');
        $form->text('payment_method', '支付方式');
        $form->text('payment_no', '支付编号');
        $form->text('refund_status', '退款状态')->default('pending');
        $form->text('refund_no', '退款编号');
        $form->switch('closed', '是否关闭');
        // $form->switch('reviewed', __('Reviewed'));
        $form->text('ship_status', '订单状态')->default('pending');

        return $form;
    }
}
