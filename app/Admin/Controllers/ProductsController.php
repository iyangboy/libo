<?php

namespace App\Admin\Controllers;

use \Admin;
use App\Admin\Actions\Products\SetAdminUser;
use App\Models\Product;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->model()->with(['adminUser', 'productSkus']);

        $grid->column('id', 'ID');
        // $grid->column('slug', 'Slug');
        $grid->column('adminUser.name', '管理员');
        $grid->column('image', '图片')->image('', 150, 150);
        $grid->column('title', '标题');
        $grid->column('long_title', '长标题');
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
        // $grid->column('rating', '评分');
        $grid->column('sold_count', '销量');
        // $grid->column('review_count', '评价数量');
        $grid->column('price', '服务费');
        $grid->column('loan_limit', '贷款限额')->display(function () {
            $mini_amount = number_format($this->mini_amount, 2, '.', ',');
            $loan_limit = number_format($this->loan_limit, 2, '.', ',');
            return '<span class="label label-success">' . $mini_amount . ' - ' . $loan_limit . '</span> ';
        });
        $grid->column('interest_rate', '日利率');
        $grid->column('by_stage', '分期选项')->modal('规格信息', function ($model) {
            $by_stages = $model->productByStage()->get()->map(function ($comment) {
                return $comment->only(['id', 'value', 'interest_rate', 'stock', 'on_sale', 'price']);
            });

            $comments = [];
            foreach ($by_stages as $key => $value) {
                $comments[$key]['id'] = $value['id'];
                $comments[$key]['value'] = $value['value'];
                $comments[$key]['interest_rate'] = $value['interest_rate'];
                $comments[$key]['stock'] = $value['stock'];
                $comments[$key]['on_sale'] = $value['on_sale'] ? '是' : '否';
                $comments[$key]['price'] = $value['price'];
            }

            return new Table(['ID','分期','日利息', '库存', '是否上线', '服务费（￥）'], $comments);
        });
        // $grid->column('specification', '规格');
        /*
        $grid->column('sku', '规格')->modal('规格信息', function ($model) {
            $skus = $model->productSkus()->get()->map(function ($comment) {
                return $comment->only(['id', 'specification', 'interest_rate', 'stock', 'on_sale', 'price']);
            });
            $comments = [];
            $specification = json_decode($this->specification, true) ?? [];

            $tabel_th = ['ID'];
            foreach ($specification as $speci) {
                $tabel_th[] = $speci['name'];
            }
            array_push($tabel_th, '日利息', '库存', '是否上线', '服务费（￥）');
            // dd($tabel_th);
            foreach ($skus as $key => $sku) {
                $comments[$key]['id'] = $sku['id'];
                // $comments[$key]['specification'] = $sku['specification'];
                foreach ($specification as $k => $speci) {
                    $comments[$key]['specification_' . $k] = $sku['specification'][$k];
                }
                $comments[$key]['interest_rate'] = $sku['interest_rate'];
                $comments[$key]['stock'] = $sku['stock'];
                $comments[$key]['on_sale'] = $sku['on_sale'] ? '是' : '否';
                $comments[$key]['price'] = $sku['price'];
            }
            // dd($comments);

            return new Table($tabel_th, $comments);
        });
        */
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        // 去掉筛选
        $grid->disableFilter();
        // 去掉导出
        $grid->disableExport();

        if (!\Admin::user()->can('set-products')) {
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

        $grid->actions(function ($actions) {
            // 检查权限
            // if (Permission::check('set-produsts-admin-user')) {
            //     $actions->add(new SetAdminUser);
            // }
            if (Admin::user()->can('set-produsts-admin-user')) {
                // 设置来源信息
                $actions->add(new SetAdminUser);
            }
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', 'ID');
        // $show->field('slug', 'Slug');
        $show->field('admin_user_id', '管理员');
        $show->field('image', '图片');
        $show->field('title', '标题');
        $show->field('long_title', '长标题');
        $show->field('description', '描述');
        $show->field('on_sale', '是否上线');
        // $show->field('rating', '评分');
        $show->field('sold_count', '销量');
        // $show->field('review_count', '评价数量');
        $show->field('price', '服务费');
        $show->field('mini_amount', '贷款限额(最小值)');
        $show->field('loan_limit', '贷款限额(最大值)');
        $show->field('interest_rate', '日利率');
        $show->field('specification', '规格');
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
        $form = new Form(new Product);

        // $form->number('admin_user_id', '管理员');
        $form->hidden('admin_user_id');
        $form->image('image', '图片');
        $form->text('title', '标题');
        $form->text('long_title', '长标题');
        $form->switch('on_sale', '是否上线')->default(1);
        // $form->decimal('rating', '评分')->default(5.00);
        // $form->number('sold_count', '销量');
        // $form->number('review_count', '评价数量');
        $form->decimal('mini_amount', '贷款限额(最小值)');
        $form->decimal('loan_limit', '贷款限额(最大值)');
        $form->decimal('interest_rate', '日利率');
        $form->decimal('price', '服务费');
        // $form->textarea('specification', '规格');
        // $form->textarea('description', '描述')->rules('required|min:10');
        $form->quill('description', '描述')->rules('required');

        // 直接添加一对多的关联模型
        $form->hasMany('productByStage', '分期选项', function (Form\NestedForm $form) {
            $form->hidden('type')->default('by_stage');
            $form->text('value', '分期数')->rules('required');
            $form->text('interest_rate', '日利息')->rules('required|numeric|min:0.0001');
            $form->text('price', '服务费')->rules('required|numeric|min:0.01');
            $form->text('stock', '剩余库存')->rules('required|integer|min:0');
            $form->switch('on_sale', '是否上线')->default(1);
        });

        // 直接添加一对多的关联模型
        // $form->hasMany('productSpecification', '规格', function (Form\NestedForm $form) {
        //     $form->text('slug', 'Slug')->rules('required');
        //     $form->text('name', '名称')->rules('required');
        //     $form->text('options', '选项')->rules('required');
        //     $form->text('default', '默认值')->rules('required');
        // });

        // // 直接添加一对多的关联模型
        // $form->hasMany('productSkus', 'SKU 列表', function (Form\NestedForm $form) {
        //     $form->text('specification', '选择规格')->rules('required');
        //     $form->text('interest_rate', '日利息')->rules('required|numeric|min:0.0001');
        //     $form->text('price', '服务费')->rules('required|numeric|min:0.01');
        //     $form->text('stock', '剩余库存')->rules('required|integer|min:0');
        //     $form->switch('on_sale', '是否上线')->default(1);
        // });

        //保存前回调
        $form->saving(function (Form $form) {
            if (!$form->admin_user_id) {
                $form->admin_user_id = Admin::user()->id;
            }
        });

        return $form;
    }
}
