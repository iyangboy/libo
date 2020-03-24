<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoanOrderStoreRequest;
use App\Http\Resources\LoanOrderResource;
use App\Models\Installment;
use App\Models\LoanOrder;
use Carbon\Carbon;

class LoanOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // return $user;
        $order = LoanOrder::where('user_id', $user->id)->get();

        return LoanOrderResource::collection($order);
    }

    // 创建订单
    public function store(LoanOrderStoreRequest $request)
    {
        // $remark = $request->remark ?? '';
        $total_amount = $request->total_amount ?? '';
        $staging = $request->staging ?? 1;
        $interest_rate = $request->interest_rate ?? 1;

        $user = $request->user();

        $order = new LoanOrder([
            'user_id'       => $user->id,
            // 'remark'       => $remark,
            'total_amount'  => $total_amount,
            'staging'       => $staging,
            'interest_rate' => $interest_rate,
        ]);

        $order->save();

        $count = $staging;
        $fee_rate = $interest_rate;
        $fine_rate = 0.001;

        $total_amount = $total_amount;

        Installment::query()
            ->where('type', 'loan')
            ->where('order_id', $order->id)
            ->where('status', Installment::STATUS_PENDING)
            ->delete();

        // 创建一个新的分期付款对象
        $installment = new Installment([
            'type'         => 'loan',
            // 总本金即为商品订单总金额
            'total_amount' => $total_amount,
            // 分期期数
            'count'        => $count,
            // 期数的费率
            'fee_rate'     => $fee_rate,
            // 逾期费率
            'fine_rate'    => $fine_rate,
            // 'order_id'     => $order->id,
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
        return $order;
    }

    // 订单详情
    public function show($id, Request $request)
    {
        $user = $request->user();
        $order = LoanOrder::where('user_id', $user->id)->find($id);

        return new LoanOrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
