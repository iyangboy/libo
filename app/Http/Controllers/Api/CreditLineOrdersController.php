<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreditLineOrderRequest;
use App\Http\Resources\CreditLineOrderResource;
use App\Models\CreditLineOrder;
use App\Models\CreditLineProduct;
use App\Models\Order;

class CreditLineOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreditLineOrderRequest $request)
    {
        $product_id = $request->product_id;
        $payment_method = $request->payment_method;

        $product = CreditLineProduct::where('on_sale', 1)->find($product_id);
        // return $product;
        if (empty($product)) {
            return response()->json([
                'message' => '该商品已下架',
            ], 403);
        }

        $user = $request->user();

        $order = new CreditLineOrder([
            'product_id'     => $product_id,
            // 'payment_method' => $payment_method,
            'total_amount'     => $product->price,
        ]);

        $order->user()->associate($user);
        // 写入数据库
        $re = $order->save();

        if (!$re) {
            return response()->json([
                'message' => '下单失败',
            ], 403);
        }

        return response()->json([
            'message' => '下单成功',
            'data' => new CreditLineOrderResource($order),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
