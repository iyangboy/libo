<?php

namespace App\Admin\Controllers;

use Admin;
use App\Models\UserVip;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserVipOrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '购买Vip列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserVip);

        $grid->model()->with(['user', 'vipProduct']);

        $grid->column('id', 'ID');
        $grid->column('user.name', '用户');
        $grid->column('vipProduct.title', 'VIP类型');
        // $grid->column('no', '订单编号');
        $grid->column('total_amount', '支付价格');
        $grid->column('remark', '备注');
        $grid->column('payment_method', '支付类型')->display(function () {
            $payment_method = $this->payment_method ?? '';
            $text = '';
            switch ($payment_method) {
                case 'ali_pay':
                    $text .= '<span class="label label-danger">支付宝支付</span> ';
                    break;
                case 'wechat_pay':
                    $text .= '<span class="label label-warning">微信支付</span> ';
                    break;
                case 'pgw_pay':
                    $text .= '<span class="label label-success">迅联支付</span> ';
                    break;
            }
            return $text;
        });
        $grid->column('paid_at', '支付时间');
        $grid->column('payment_no', '支付编号');
        // $grid->column('closed', '知否关闭');
        $grid->column('status', '状态')->display(function () {
            $status = $this->status ?? '';
            $text = '';
            switch ($status) {
                case 'pending':
                    $text .= '<span class="label label-warning">待支付</span> ';
                    break;
                case 'failed':
                    $text .= '<span class="label label-danger">支付失败</span> ';
                    break;
                case 'success':
                    $text .= '<span class="label label-success">支付成功</span> ';
                    break;
            }
            return $text;
        })->filter([
            'pending' => '待支付',
            'failed' => '支付失败',
            'success' => '支付成功',
        ]);
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        // 去掉导出
        $grid->disableExport();

        if (!Admin::user()->can('set-user-vip-orders')) {
            $grid->tools(function ($tools) {
                // 禁用批量删除按钮
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            // 去掉新建
            $grid->disableCreateButton();
            // 去掉筛选
            $grid->disableFilter();
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
        $show = new Show(UserVip::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user_id', '用户');
        $show->field('vip_id', 'VIP类型');
        // $show->field('no', '订单编号');
        $show->field('total_amount', '支付价格');
        $show->field('remark', '备注');
        $show->field('payment_method', '支付类型');
        $show->field('paid_at', '支付时间');
        $show->field('payment_no', '支付编号');
        // $show->field('closed', '知否关闭');
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
        $form = new Form(new UserVip);

        $form->number('user_id', '用户');
        $form->number('vip_id', 'VIP类型');
        // $form->text('no', '订单编号');
        $form->decimal('total_amount', '支付价格');
        $form->textarea('remark', '备注');
        $form->datetime('paid_at', '支付时间')->default(date('Y-m-d H:i:s'));
        $form->select('payment_method', '支付类型')->options(['ali_pay' => '支付宝', 'wechat_pay' => '微信', 'pgw_pay' => '迅联支付']);
        $form->text('payment_no', '支付编号');
        $form->switch('closed', '知否关闭');
        $form->select('status', '状态')->options(['pending' => '待支付', 'failed' => '支付失败', 'success' => '支付成功'])->default('pending');

        return $form;
    }
}
