<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\Admin\CsvController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\FreeFormController;
use App\Http\Controllers\Admin\Master\ExpenseCustomItemController;
use App\Http\Controllers\Admin\Master\ExpenseItemController;
use App\Http\Controllers\Admin\Master\FfCategoryController;
use App\Http\Controllers\Admin\Master\FfDefaultController;
use App\Http\Controllers\Admin\Master\LocationController;
use App\Http\Controllers\Admin\Master\StandardController;
use App\Http\Controllers\Admin\Master\UnitController;
use App\Http\Controllers\Admin\ModeController;
use App\Http\Controllers\Admin\ProcessController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\User\PermissionController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group([
  'middleware' => ['auth:user'],
], function () {

  // モード変更
  //----------------------------------
  Route::group([
    'controller' => ModeController::class,
    'as' => 'api.',
  ], function() {
    Route::post('mode', 'modeChange')->name('modeChange');
  });
  Route::post('mode/{mode}', fn() => '')->name('mode.dummy');

  // 現場
  //----------------------------------
  Route::group([
    'controller' => ProjectController::class,
  ], function() {
    Route::get('', 'index')->name('index');
    // セッションリセット
    Route::get('reset', 'reset')->name('project.reset');
    Route::group([
      'as' => 'project.',
      'prefix' => 'project'
    ], function() {
      // ［非同期］ユーザーリスト取得
      Route::post('getUser', 'getUser')
        ->name('api.getUser');
    });
  });
  Route::resource('project', ProjectController::class)
    ->except('index');

  // 工区・工種
  //----------------------------------
  Route::group([
    'controller' => FreeFormController::class,
    'as' => 'free_form.',
    'prefix' => 'free_form'
  ], function() {
    Route::get('', 'index')->name('index');
    // ［非同期］計算結果取得
    Route::post('getResult', 'getResult')
      ->name('api.getResult');
  });
  Route::resource('free_form', FreeFormController::class)
    ->except('index', 'show');

  // 資材搬入
  //----------------------------------
  Route::group([
    'controller' => DeliveryController::class,
    'as' => 'delivery.',
    'prefix' => 'delivery'
  ], function() {
    Route::get('add/{process_item}', 'add')->name('add');
    // ［非同期］単位取得
    Route::post('getUnit', 'getUnit')->name('api.getUnit');
  });
  Route::resource('delivery', DeliveryController::class)
    ->except('destroy');

  // 作業実績登録
  //----------------------------------
  Route::group([
    'controller' => CsvController::class,
    'as' => 'csv.',
    'prefix' => 'csv'
  ], function() {
    Route::get('', 'index')->name('index');
    Route::get('create/{process_type}', 'create')->name('create');
    Route::post('', 'store')->name('store');
    // アップロード
    Route::post('upload', 'upload')->name('upload');
    Route::delete('{process_term}', 'destroy')->name('destroy');
  });

  // 工程
  //----------------------------------
  Route::group([
    'controller' => ProcessController::class,
    'as' => 'process.',
    'prefix' => 'process'
  ], function() {
    // 一覧
    Route::get('', 'index')->name('index');
    // 編集
    Route::get('{type}/{term?}', 'edit')->name('edit');
    // edit3コメントからedit2編集へ
    Route::get('change/{type}/{term}', 'change')->name('change');
    // 更新
    Route::put('{process}', 'update')->name('update');
    // ［非同期］mode1：計算
    Route::POST('calculate', 'calculate')->name('api.calculate');
    // ［非同期］mode2：コメントフォーム追加
    Route::post('addCommentForm', 'addCommentForm')->name('api.addCommentForm');
    // ［非同期］mode2：歩掛かり取得
    Route::post('getRate', 'getRate')->name('api.getRate');
    // ［非同期］mode2：材料費各種計算
    Route::post('materialCalculate', 'materialCalculate')->name('api.materialCalculate');
  });

  // 資材搬入
  //----------------------------------
  Route::group([
    'controller' => ChartController::class,
    'as' => 'chart.',
    'prefix' => 'chart'
  ], function() {
    Route::get('', 'index')->name('index');
  });

  // マスター
  //----------------------------------
  Route::group([
    'prefix' => 'master',
    'as' => 'master.',
  ], function() {
    // 費用項目
    Route::resource('expense_item', ExpenseItemController::class)->except('show', 'destroy');

    // カスタム工程費用項目
    Route::group([
      'controller' => ExpenseCustomItemController::class,
      'prefix' => 'expense_custom_item',
      'as' => 'expense_custom_item.',
    ], function() {
      // ［非同期］追加
      Route::POST('', 'store')->name('api.store');
      // ［非同期］削除
      Route::POST('delete', 'destroy')->name('api.destroy');
    });

    // 規格
    Route::resource('standard', StandardController::class)
      ->except('show', 'destroy');

    // 単位
    Route::resource('unit', UnitController::class)
      ->except('show', 'destroy');

    // フリーフォームデフォルト
    Route::group([
      'as' => 'ff_default.',
      'prefix' => 'ff_default',
      'controller' => FfDefaultController::class,
    ], function () {
      // ［非同期］規格名取得
      Route::post('getStandard', 'getStandard')
        ->name('api.getStandard');
    });
    Route::resource('ff_default', FfDefaultController::class)
      ->except('show', 'destroy');

    // フリーフォームカテゴリ
    Route::resource('ff_category', FfCategoryController::class)
      ->except('show', 'destroy');

    // 拠点管理
    Route::resource('location', LocationController::class)
      ->except('show', 'destroy');
  });

  // ユーザー管理
  //----------------------------------
  Route::group([
    'prefix' => 'user',
    'as' => 'user.',
  ], function() {
    Route::resources([
      'permission' => PermissionController::class,
      'role' => RoleController::class,
      'user' => UserController::class,
    ]);
  });

  // パスワード変更
  //----------------------------------
  Route::get('change-password', [PasswordResetLinkController::class, 'create'])->name('change_password.index');
});

// ログイン/ログアウト
//----------------------------------
Route::group([
  'controller' => LoginController::class,
], function () {
  Route::group([
    'middleware' => ['guest:user'],
  ], function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
  });
  Route::post('logout', 'logout')->name('logout');
});

// パスワード忘れ
//----------------------------------
Route::group([
  'as' => 'password.',
  'middleware' => ['guest:user']
], function () {
  // フォーム表示
  Route::group([
    'prefix' => 'forgot-password',
    'controller' => NewPasswordController::class,
  ], function () {
    // ログインからのリンクによるフォームの表示
    Route::get('', 'index');
    // メール送信
    Route::post('email', 'email')->name('email');
    // メール記載のメールアドレスからのフォームの表示
    Route::get('{token}', 'create')->name('reset');
    // フォームによるリセット
    Route::post('', 'store')->name('store');
  });
});
