<?php

namespace App\Admin\Controllers;

use \Admin;
use App\Models\Agreement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AgreementsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '协议';

    public function show($id, Content $content)
    {
        return $content
            ->header('协议')
            ->description('详情信息')
            ->body(view('admin.agreements.show', ['agreement' => Agreement::find($id)]));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Agreement);

        $grid->column('id', 'ID');
        // $grid->column('admin_user_id', '管理员');
        // $grid->column('slug', __('Slug'));
        // $grid->column('type', '类型');
        $grid->column('title', '标题');
        $grid->column('content', '内容')->display(function () {
            $content = make_excerpt($this->content, 20) . '...';
            $text = '<a href="' . route('admin.agreements.show', [$this->id]) . '">' . $content . '</a>';
            return $text;
        });
        $grid->column('on_sale', '是否上线')->display(function ($value) {
            return $value ? '是' : '否';
        })->label([
            0 => 'warning',
            1 => 'success',
        ])->filter([
            0 => '否',
            1 => '是',
        ]);
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

        if (!Admin::user()->can('set-agreements')) {
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
        $show = new Show(Agreement::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('admin_user_id', '管理员');
        // $show->field('slug', __('Slug'));
        // $show->field('type', '类型');
        $show->field('title', '标题');
        $show->field('content', '内容');
        $show->field('on_sale', '是否上线');
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
        $form = new Form(new Agreement);

        $form->number('admin_user_id', '管理员');
        // $form->text('slug', __('Slug'));
        // $form->text('type', '类型');
        $form->text('title', '标题');
        $form->textarea('content', '内容');
        $form->switch('on_sale', '是否上线')->default(1);

        return $form;
    }
}
