<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CreditLineResource;
use App\Models\CreditLineProduct;
use Doctrine\DBAL\Schema\Index;

class CreditLinesController extends Controller
{
    //
    public function index()
    {
        $creditLines = CreditLineProduct::where('on_sale', 1)->get();

        return response()->json([
            'data' => CreditLineResource::collection($creditLines),
        ], 200);
    }
}
