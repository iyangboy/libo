<?php

namespace App\Console\Commands;

use App\Models\UserStatistic;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateUserStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'libo:calculate-user-statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成用户统计';

    // 最终执行的方法
    public function handle()
    {
        // 在命令行打印一行信息
        $this->info("开始计算...");

        $today = Carbon::today()->subDays(1)->toDateString();

        // $statistic = UserStatistic::where('day_at', $today)->first();
        $statistic = UserStatistic::firstOrCreate(['day_at' => $today]);
        if ($statistic) {
            $statistic->increment('uv', $statistic->visits()->count());
            // 清除项目的访问次数
            $statistic->visits()->reset();
        }

        // 生成一个新的统计
        UserStatistic::firstOrCreate(['day_at' => Carbon::today()->toDateString()]);

        $this->info("成功生成！");
    }
}
