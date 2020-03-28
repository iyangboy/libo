<?php

namespace App\Admin\Actions\Sources;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SetSources extends RowAction
{
    public $name = '设置来源信息';

    public function handle(Model $model, Request $request)
    {
        // $model ...
        $admin_user_id = $request->admin_user_id ?? 0;
        $slug = $request->slug ?? '';
        $user_count_decrement_base = $request->user_count_decrement_base ?? 0;

        // $model->admin_user_id = $admin_user_id;
        // $model->slug = $slug;
        // $model->user_count_decrement_base = $user_count_decrement_base;
        // $model->save();

        try {
            // 处理逻辑...
            if ($admin_user_id) {
                $model->admin_user_id = $admin_user_id;
            }
            if ($slug) {
                $model->slug = $slug;
            }
            if ($user_count_decrement_base) {
                $model->user_count_decrement_base = $user_count_decrement_base;
            }
            $model->save();
            return $this->response()->success('操作成功')->refresh();
        } catch (\Exception $e) {
            return $this->response()->error('产生错误：'.$e->getMessage());
        }
        return $this->response()->success('操作成功')->refresh();
    }

    public function form(Model $model)
    {
        $this->select('admin_user_id', '选择管理员')->options(route('admin.select_admin_user'))->default($model->admin_user_id);

        $this->text('slug', 'Slug')->default($model->slug)->rules('required|unique:sources,slug,' . $model->id);
        $this->text('user_count_decrement_base', '扣除基数')->default($model->user_count_decrement_base);
    }

}
