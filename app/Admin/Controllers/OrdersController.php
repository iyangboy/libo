<?php

namespace App\Admin\Controllers;

use \Admin;
use App\Admin\Actions\Orders\SetInstallment;
use App\Exceptions\InvalidRequestException;
use App\Models\Installment;
use App\Models\Order;
use Carbon\Carbon;
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
        $grid->column('payment_method', '收款方式')->display(function () {
            $payment_method = $this->payment_method ?? '';
            $text = '';
            switch ($payment_method) {
                case 'alipay':
                    $text .= '<span class="label label-danger">支付宝</span> ';
                    break;
                case 'weixin':
                    $text .= '<span class="label label-warning">微信</span> ';
                    break;
                case 'brank':
                    $text .= '<span class="label label-success">银联</span> ';
                    break;
            }
            return $text;
        });
        $grid->column('payment_no', '支付编号');
        // $grid->column('refund_status', '退款状态');
        // $grid->column('refund_no', '退款编号');
        $grid->column('closed', '是否关闭');
        // $grid->column('reviewed', __('Reviewed'));
        $grid->column('ship_status', '状态')->display(function () {
            $status = $this->ship_status ?? '';
            $text = '';
            switch ($status) {
                case 'pending':
                    $text .= '<span class="label label-warning">待处理</span> ';
                    break;
                case 'failed':
                    $text .= '<span class="label label-danger">处理失败</span> ';
                    break;
                case 'success':
                    $text .= '<span class="label label-success">处理成功</span> ';
                    break;
            }
            return $text;
        })->filter([
            'pending' => '待支付',
            'failed' => '支付失败',
            'success' => '支付成功',
        ]);
        // $grid->column('ship_data', __('Ship data'));
        // $grid->column('extra', __('Extra'));
        $grid->column('created_at', '创建时间');
        // $grid->column('updated_at', '更新时间');

        // 去掉导出
        $grid->disableExport();

        if (!\Admin::user()->can('set-orders')) {
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

        $grid->actions(function ($actions) {
            // 检查权限
            // if (Permission::check('set-sources')) {
            //
            //     $actions->add(new SetSources);
            // }
            if (\Admin::user()->can('set-orders')) {
                // 设置来源信息
                $actions->add(new SetInstallment);
            }
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
        $form->select('product_by_stage_id', '选择分期')->options(route('admin.select_products_by_stages', [1]));;
        // $form->number('by_stage', '分期数')->default(1);
        $form->textarea('remark', '备注');
        $form->datetime('paid_at', '支付时间')->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', '支付方式');
        $form->text('payment_no', '支付编号');
        // $form->text('refund_status', '退款状态')->default('pending');
        // $form->text('refund_no', '退款编号');
        $form->switch('closed', '是否关闭');
        $form->text('ship_status', '订单状态')->default('pending');

        return $form;
    }

    // 分期处理
    public function modeInstallment(Order $order, $count = 1)
    {
        // 判断订单是否属于当前用户
        // $this->authorize('own', $order);
        // 订单已支付或者已关闭
        // if ($order->paid_at || $order->closed) {
        //     throw new InvalidRequestException('订单状态不正确');
        // }

        // 删除同一笔商品订单发起过其他的状态是未支付的分期付款，避免同一笔商品订单有多个分期付款
        Installment::query()
            ->where('order_id', $order->id)
            ->where('status', Installment::STATUS_PENDING)
            ->delete();

        // 创建一个新的分期付款对象
        $installment = new Installment([
            // 总本金即为商品订单总金额
            'total_amount' => $order->loan_amount,
            // 分期期数
            'count'        => $count,
            // 从配置文件中读取相应期数的费率
            'fee_rate'     => $order->product->interest_rate ?? 0.0001,
            // 从配置文件中读取当期逾期费率
            'fine_rate'    => $order->product->interest_fine_rate ?? 0.0001,
        ]);
        $installment->user()->associate($order->user());
        $installment->order()->associate($order);
        $installment->save();
        // 第一期的还款截止日期为明天凌晨 0 点
        $dueDate = Carbon::tomorrow();
        // 计算每一期的本金
        $base = big_number($order->loan_amount)->divide($count)->getValue();
        // 计算每一期的手续费
        $fee = big_number($base)->multiply($installment->fee_rate)->divide(100)->getValue();
        // 根据用户选择的还款期数，创建对应数量的还款计划
        for ($i = 0; $i < $count; $i++) {
            // 最后一期的本金需要用总本金减去前面几期的本金
            if ($i === $count - 1) {
                $base = big_number($order->loan_amount)->subtract(big_number($base)->multiply($count - 1));
            }
            $installment->items()->create([
                'sequence' => $i,
                'base'     => $base,
                'fee'      => $fee,
                'due_date' => $dueDate,
            ]);
            // 还款截止日期加 30 天
            $dueDate = $dueDate->copy()->addDays(30);
        }

        return $installment;
    }

}
