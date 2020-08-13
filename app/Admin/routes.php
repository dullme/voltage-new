<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    //组件零件
    $router->resource('components', ComponentController::class);
    $router->get('component-list', 'ComponentController@getComponentList');

    //线束设计
    $router->resource('harnesses', HarnessController::class);
    $router->get('harnesses/{harness}', 'HarnessController@detailInfo');
    $router->post('search-harness', 'HarnessController@searchHarness');

    //Typical
    $router->resource('typicals', TypicalController::class);
    $router->get('typicals/{typical}', 'TypicalController@detailInfo');
    $router->post('stage-save', 'TypicalController@stageSave');
    $router->post('stage-submit', 'TypicalController@stageSubmit');

    //公司
    $router->resource('companies', CompanyController::class);
    $router->get('company-list', "CompanyController@getCompany");

    //公司联系人
    $router->resource('company-contacts', CompanyContactController::class);

    //太阳能板种类
    $router->resource('solar-panels', SolarPanelController::class);
    $router->get('solar-panel-list', 'SolarPanelController@getSolarPanel');

    //支架种类
    $router->resource('brackets', BracketController::class);
    $router->get('bracket-list', 'BracketController@getBracket');

    //项目管理
    $router->resource('projects', ProjectController::class);
    $router->get('projects/info/{id}', "ProjectController@info");
    $router->post('projects/search/typical', "ProjectController@searchTypical");
});
