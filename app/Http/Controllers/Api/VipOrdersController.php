<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PurchaseVipOrderRequest;
use App\Models\MemberProduct;
use App\Models\UserVip;

class VipOrdersController extends Controller
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
    public function store(Request $request)
    {
        //
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

    // 购买 vip
    public function purchaseVip($vip_id, Request $request)
    {
        $user_id = $request->user()->id;
        $memberProduct = MemberProduct::where('on_sale', 1)->find($vip_id);

        if (!$memberProduct) {
            return response()->json([
                'error' => ['该VIP已下架'],
            ], 403);
        }

        $order = new UserVip();
        $order->user_id      = $user_id;
        $order->vip_id       = $memberProduct->id;
        $order->total_amount = $memberProduct->price;
        $order->user_id = $user_id;
        $order->user_id = $user_id;
        $order->save();

        return $memberProduct;
        return $user_id;
    }
}
