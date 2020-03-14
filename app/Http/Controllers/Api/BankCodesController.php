<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankCodeResource;
use App\Models\BankCode;

class BankCodesController extends Controller
{
    //
    public function index()
    {
        $bankCodes = BankCode::where('on_sale', 1)->get();

        return BankCodeResource::collection($bankCodes);
    }
}
