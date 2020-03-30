<?php

namespace App\Admin\Actions\Statistics;

use App\Models\UserStatistic;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class SetUVSynchro extends RowAction
{
    public $name = 'uv-同步';

    public function handle(Model $model)
    {
        try {
            $today = Carbon::today()->toDateString();

            // $statistic = UserStatistic::where('day_at', $today)->first();
            $statistic = UserStatistic::firstOrCreate(['day_at' => $today]);
            if ($statistic) {
                $statistic->increment('uv', $statistic->visits()->count());
                // 清除项目的访问次数
                $statistic->visits()->reset();
            }

            return $this->response()->success('成功...')->refresh();
        } catch (\Exception $e) {
            return $this->response()->error('产生错误：' . $e->getMessage());
        }
    }
}
