<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\UVSource;
use Carbon\Carbon;

class SourcesController extends Controller
{
    //
    public function show($slug, Request $request)
    {
        $source = Source::where('slug', $slug)->first();

        if ($source) {
            $today = Carbon::today()->toDateString();
            $ip = $request->getClientIp();
            UVSource::firstOrCreate([
                'source_id' => $source->id,
                'day_at' => $today,
                'ip' => $ip,
            ]);
        }

        return response()->json([
            'data'    => true,
        ], 200);
    }
}
