<?php

namespace App\Http\Controllers;

use App\Models\UserStatistic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SourcesController extends Controller
{
    //
    public function show($slug, Request $request)
    {
        $today = Carbon::today()->toDateString();

        // $statistic = UserStatistic::where('day_at', $today)->first();
        $statistic = UserStatistic::firstOrCreate(['day_at' => $today]);

        // $statistic->increment('uv');
        $statistic->visits()->increment();

        // dd($statistic->uv);
        // dd($statistic->visits()->count());

        return redirect('sources/' . $slug . '/index.html');

    }

}
