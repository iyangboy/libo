<?php

namespace App\Admin\Controllers;

use Admin;
use App\Models\CreditLineProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CreditLineProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '授信额度';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CreditLineProduct);

        $grid->model()->with(['adminUser']);

        $grid->column('id', 'ID');
        // $grid->column('adminUser.name', '管理员');
        $grid->column('title', '标题');
        // $grid->column('long_title', '长标题');
        // $grid->column('description', '描述');
        $grid->column('image', '图片')->image('', 100, 100);
        $grid->column('on_sale', '是否上线')->display(function ($value) {
            return $value ? '是' : '否';
        })->label([
            0 => 'warning',
            1 => 'success',
        ])->filter([
            0 => '否',
            1 => '是',
        ]);
        $grid->column('sold_count', '销量');
        $grid->column('market_price', '市场价')->display(function ($value) {
            $text = '<del>' . $value . '</del>';
            return $text;
        })->label('warning');
        $grid->column('price', '售价')->label();
        $grid->column('quota_min', '限额')->display(function () {
            return $this->quota_min . ' ~ ' . $this->quota_max;
        });
        // $grid->column('quota_max', '限额最大值');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

        if (!\Admin::user()->can('set-credit-line-products')) {
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
        $show = new Show(CreditLineProduct::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('admin_user_id', '管理员');
        $show->field('title', '标题');
        $show->field('long_title', '长标题');
        $show->field('description', '描述');
        $show->field('image', '图片');
        $show->field('on_sale', '是否上线');
        $show->field('sold_count', '销量');
        $show->field('market_price', '市场价');
        $show->field('price', '售价');
        $show->field('quota_min', '限额最小值');
        $show->field('quota_max', '限额最大值');
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
        $form = new Form(new CreditLineProduct);

        $form->hidden('admin_user_id');
        // $form->number('admin_user_id', '管理员');
        $form->text('title', '标题');
        $form->text('long_title', '长标题');
        $form->textarea('description', '描述');
        $form->image('image', '图片');
        $form->switch('on_sale', '是否上线')->default(1);
        if (\Admin::user()->can('admin-set-credit-line-products')) {
            // 设置销量
            $form->number('sold_count', '销量');
        }
        $form->decimal('market_price', '市场价');
        $form->decimal('price', '售价');
        $form->decimal('quota_min', '限额最小值');
        $form->decimal('quota_max', '限额最大值');

        //保存前回调
        $form->saving(function (Form $form) {
            if (!$form->admin_user_id) {
                $form->admin_user_id = \Admin::user()->id;
            }
        });

        return $form;
    }
}
