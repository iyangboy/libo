<?php

namespace App\Admin\Controllers;

use App\Models\MemberProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MemberProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员类型列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MemberProduct);

        $grid->column('id', 'ID');
        // $grid->column('slug', 'Slug');
        $grid->column('image', '推广图')->image('', 200, 200);
        $grid->column('title', '标题');
        $grid->column('description', '描述');
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
        $grid->column('price', '价格')->label('success');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            // $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });

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
        $show = new Show(MemberProduct::findOrFail($id));

        $show->field('id', 'ID');
        // $show->field('slug', 'Slug');
        $show->field('image', '推广图');
        $show->field('title', '标题');
        $show->field('description', '描述');
        $show->field('on_sale', '是否上线');
        $show->field('sold_count', '销量');
        $show->field('price', '价格');
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
        $form = new Form(new MemberProduct);

        // $form->text('slug', __('Slug'));
        $form->image('image', __('Image'));
        $form->text('title', '标题');
        $form->textarea('description', '描述');
        // $form->quill('description', '内容');
        $form->switch('on_sale', '是否上线')->default(1);
        // $form->decimal('price', '价格');
        $form->text('price', '价格');

        return $form;
    }
}
