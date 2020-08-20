<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

include_once 'helpers.php';

use Encore\Admin\Grid;
use Encore\Admin\Form;

Encore\Admin\Form::forget(['map', 'editor']);
Encore\Admin\Admin::js('/js/admin.js');
Encore\Admin\Admin::css('/css/admin.css');
Encore\Admin\Admin::html('<div id="loading" style="display: none"><div style="position: absolute;top:0;bottom:0;right:0;left:0;background:rgba(0,0,0,0.4);z-index:5999;"></div><div class="dialog" style="position: fixed;top: 50%;left: 50%;z-index: 6000;color: #FFFFFF"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div></div>');

Grid::init(function (Grid $grid) {
    $grid->model()->orderByDesc('id');

//    $grid->disableActions(); //隐藏操作按钮

//    $grid->disablePagination(); //隐藏分页

//    $grid->disableCreateButton();

    $grid->disableFilter(); //隐藏搜索

    $grid->disableRowSelector(); //隐藏多选

    $grid->disableColumnSelector(); //隐藏列选择

//    $grid->disableTools();

    $grid->disableExport(); //隐藏导出

    $grid->actions(function (Grid\Displayers\Actions $actions) {
        $actions->disableView();
//        $actions->disableEdit();
        $actions->disableDelete();
    });
});

Form::init(function (Form $form) {

    $form->disableEditingCheck();

    $form->disableCreatingCheck();

    $form->disableViewCheck();

    $form->tools(function (Form\Tools $tools) {
        $tools->disableDelete();
        $tools->disableView();
//        $tools->disableList();
    });
});

ignore_pjax_paths([
    'projects\/.*',
]);
