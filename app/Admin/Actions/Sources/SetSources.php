<?php

namespace App\Admin\Actions\Sources;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class SetSources extends RowAction
{
    public $name = '设置来源信息';

    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->success('Success message.')->refresh();
    }

}