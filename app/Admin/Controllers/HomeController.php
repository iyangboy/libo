<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\User;
use App\Models\UserStatistic;
use Carbon\Carbon;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Content $content, Request $request)
    {
        // 用户注册数量
        $users = User::query()->with(['userInfo']);
        // search 参数用来模糊搜索商品
        if ($search = $request->input('start_time', '')) {
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $users->where('created_at', '>', $search);
        }
        if ($search = $request->input('end_time', '')) {
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $users->where('created_at', '<', $search);
        }
        $users = $users->get();
        // dd($users->toArray());
        // 用户注册总数
        $user_count = $users->count();
        // 用户会员人数
        $user_grade_count = $users->where('grade_id', '>', 0)->count();
        // 用户绑定身份证人数
        $user_id_card_count = $users->where('id_card', '>', 0)->count();
        // 基本信息填写人数
        // $user_info_count = $users->where('userInfo', '!=', null)->count();
        // $user_info_count = User::whereHas('userInfo', function (Builder $query) {
        //     $query->where('user_id', '>', 0);
        // })->count();
        $user_info = User::query()->whereHas('userInfo', function (Builder $query) {
            $query->where('user_id', '>', 0);
        });
        if ($search = $request->input('start_time', '')) {
            $user_info->where('created_at', '>', $search);
        }
        if ($search = $request->input('end_time', '')) {
            $user_info->where('created_at', '<', $search);
        }
        $user_info_count = $user_info->count();
        // 绑卡成功数
        // $user_bank_card_count = User::whereHas('userBankCards', function (Builder $query) {
        //     $query->where('protocol_id', '>', 0);
        // })->count();
        $user_bank_card = User::query()->whereHas('userBankCards', function (Builder $query) {
            $query->where('protocol_id', '>', 0);
        });
        if ($search = $request->input('start_time', '')) {
            $user_bank_card->where('created_at', '>', $search);
        }
        if ($search = $request->input('end_time', '')) {
            $user_bank_card->where('created_at', '<', $search);
        }
        $user_bank_card_count = $user_bank_card->count();
        // dd($user_bank_card_count);

        // 区分来源
        // 来源
        $sources = Source::query()->with(['adminUser', 'users', 'uv']);
        if ($search = $request->input('start_time', '')) {
            $sources->where('created_at', '>', $search);
        }
        if ($search = $request->input('end_time', '')) {
            $sources->where('created_at', '<', $search);
        }
        $sources = $sources->get();

        // uv-来源
        $today = Carbon::today()->toDateString();

        // $statistic = UserStatistic::where('day_at', $today)->first();
        $statistic = UserStatistic::firstOrCreate(['day_at' => $today]);

        // $statistic->increment('uv');
        // $statistic->visits()->increment();
        $uv_source = $statistic->visits()->count();

        // dd($statistic->uv);
        // dd($statistic->visits()->count());

        // $user_statistics = $this->statistics();
        // $user_statistics_key = collect($user_statistics)->keys();
        // $user_statistics_value = collect($user_statistics)->values();
        // dd($user_statistics_value);
        $data = [
            'request'               => $request,

            'user_count'            => $user_count,
            'user_grade_count'      => $user_grade_count,
            'user_id_card_count'    => $user_id_card_count,
            'user_info_count'       => $user_info_count,
            'user_bank_card_count'  => $user_bank_card_count,

            'sources'               => $sources,
            'uv_source'             => $uv_source,

            // 'user_statistics'       => $user_statistics,
            // 'user_statistics_key'   => $user_statistics_key,
            // 'user_statistics_value' => $user_statistics_value,
        ];

        return $content
            ->header('控制面板')
            ->description('')
            ->body(view('admin.homes.index', $data));

        return $content->title('控制面板');
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }

    // 用户注册量
    public function statistics()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--){
            $today = Carbon::today();
            $day = $today->subDays($i)->toDateString();
            if ($i) {
                $today_end = Carbon::today();
                $day_end = $today_end->subDays($i-1)->toDateString();
                $count = User::with(['userInfo'])->where('created_at', '>', $day)->where('created_at', '<', $day_end)->count();
            } else {
                $count = User::with(['userInfo'])->where('created_at', '>', $day)->count();
            }

            $data[$day] = $count;
        }
        return $data;
    }
}
