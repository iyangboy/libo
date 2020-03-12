<?php

namespace App\Admin\Controllers;

use App\Models\BankCode;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BankCodesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '支持银行';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BankCode);

        $grid->column('id', 'ID');
        $grid->column('code', '讯联机构号');
        $grid->column('name', '名称');
        $grid->column('on_sale', '是否使用')->display(function ($value) {
            return $value ? '是' : '否';
        })->label([
            0 => 'warning',
            1 => 'success',
        ]);
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

        if (!\Admin::user()->can('set-bank-codes')) {
            $grid->tools(function ($tools) {
                // 禁用批量删除按钮
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            // 去掉新建
            $grid->disableCreateButton();

            // 关闭操作
            $grid->disableActions();
        }

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(BankCode::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('code', '讯联机构号');
        $show->field('name', '名称');
        $show->field('on_sale', '是否使用');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BankCode);

        $form->text('code', '讯联机构号');
        $form->text('name', '名称');
        $form->switch('on_sale', '是否使用')->default(1);

        return $form;
    }
}
