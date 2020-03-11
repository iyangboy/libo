<?php

namespace App\Admin\Actions\Orders;

use App\Models\Installment;
use App\Models\Order;
use App\Models\ProductByStage;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SetInstallment extends RowAction
{
    public $name = '分期处理-确认';

    public function handle(Model $model, Request $request)
    {
        $products_by_stage_id = $request->products_by_stage_id ?? 0;
        // $model ...

        $rs = $this->modeInstallment($model, $products_by_stage_id);

        return $this->response()->success('操作完成')->refresh();
    }

    public function form(Model $model)
    {
        $this->select('products_by_stage_id', '选择分期')->options(route('admin.select_products_by_stages', [$model->product->id]));
    }

    // 分期处理
    public function modeInstallment(Order $order, $by_stage_id = 0)
    {
        $byStage = ProductByStage::where('on_sale', 1)->where('product_id', $order->product_id)->find($by_stage_id);

        $count = $byStage->value ?? 1;
        $fee_rate = $byStage->interest_rate ?? 0.0001;
        $fine_rate = $byStage->interest_fine_rate ?? 0.0001;

        $total_amount = $order->loan_amount ?? 0;
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
            'total_amount' => $total_amount,
            // 分期期数
            'count'        => $count,
            // 期数的费率
            'fee_rate'     => $fee_rate,
            // 逾期费率
            'fine_rate'    => $fine_rate,
        ]);
        $installment->user()->associate($order->user_id);
        $installment->order()->associate($order);
        $installment->save();

        // 第一期的还款截止日期为明天凌晨 0 点
        $dueDate = Carbon::tomorrow();
        // 计算每一期的本金
        $base = big_number($total_amount)->divide($count)->getValue();
        // 计算每一期的手续费 加法 add()、减法 subtract()、乘法 multiply()、除法 divide()
        $fee = big_number($base)->multiply($fee_rate)->multiply(30)->getValue();
        // 根据用户选择的还款期数，创建对应数量的还款计划
        for ($i = 0; $i < $count; $i++) {
            // 最后一期的本金需要用总本金减去前面几期的本金
            if ($i === $count - 1) {
                $base = big_number($total_amount)->subtract(big_number($base)->multiply($count - 1));
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
