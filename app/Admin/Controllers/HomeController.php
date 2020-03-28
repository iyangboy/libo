<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        // 用户注册数量
        $users = User::with(['userInfo'])->get();
        // dd($users->toArray());
        // 用户注册总数
        $user_count = $users->count();
        // 用户会员人数
        $user_grade_count = $users->where('grade_id', '>', 0)->count();
        // 用户绑定身份证人数
        $user_id_card_count = $users->where('id_card', '>', 0)->count();
        // 基本信息填写人数
        // $user_info_count = $users->where('userInfo', '!=', null)->count();
        $user_info_count = User::whereHas('userInfo', function (Builder $query) {
            $query->where('user_id', '>', 0);
        })->count();
        // 绑卡成功数
        $user_bank_card_count = User::whereHas('userBankCards', function (Builder $query) {
            $query->where('protocol_id', '>', 0);
        })->count();
        // dd($user_bank_card_count);

        // 区分来源
        // 来源
        $sources = Source::with(['adminUser', 'users'])->get();

        // 添加时间筛选
        $today = Carbon::today();
        // 用户注册数量
        $users = User::with(['userInfo'])->where('created_at', '>', $today->subDays(6))->get();
        dd($users->count());

        $data = [
            'user_count'           => $user_count,
            'user_grade_count'     => $user_grade_count,
            'user_id_card_count'   => $user_id_card_count,
            'user_info_count'      => $user_info_count,
            'user_bank_card_count' => $user_bank_card_count,

            'sources'              => $sources,
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
}
