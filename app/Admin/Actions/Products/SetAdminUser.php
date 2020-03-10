<?php

namespace App\Admin\Actions\Products;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SetAdminUser extends RowAction
{
    public $name = '设置管理员';

    public function handle(Model $model, Request $request)
    {
        $admin_user_id = $request->admin_user_id ?? 0;
        $slug = $request->slug ?? '';

        $model->admin_user_id = $admin_user_id;
        $model->slug = $slug;

        $model->save();

        return $this->response()->success('操作成功')->refresh();
    }

    public function form(Model $model)
    {
        $this->select('admin_user_id', '选择管理员')->options(route('admin.select_admin_user'))->default($model->admin_user_id);

        $this->text('slug', 'Slug')->default($model->slug)->rules('required|unique:products,slug,' . $model->id);
    }

}
