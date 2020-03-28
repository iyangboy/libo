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

        $statistics = UserStatistic::where('day_at', $today)->first();
        $statistics->visits()->increment();

        // dd($statistics->visits()->count());

        return redirect('sources/' . $slug . '/index.html');

    }
}
