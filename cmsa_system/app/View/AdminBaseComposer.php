<?php

namespace App\View;

use App\Libs\projectParams;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AdminBaseComposer {
  /**
   * @param View $view
   *
   * @return void
   */
  public function compose(View $view)
  {
    $current = Route::current();
    $route_name = Null;
    $page_id = Null;
    $process_type = Null;

    if($current) {
      $route_name = $current->getName();
      // トップ判定
      if($route_name=='admin.index') {
        $page_id = 'home';
      } elseif(Lang::has($route_name . '._page_id')) {
        $page_id = __($route_name . '._page_id');
      }
      // 工程用
      $params = $current->parameters ?? [];
      if(!empty($params) && isset($params['type'])) {
        $process_type = $params['type'];
      }
    }

    $pp = new projectParams();
    $view->with([
      'route_name' => $route_name,
      'page_id' => $page_id,
      // 現在のモード
      'mode_num' => $pp->nowModeNum(),
      // 現在選択中のプロジェクト
      'now_project' => $pp->nowProject(),
      // 現在選択中のフリーフォーム
      'now_free_form' => $pp->nowFreeForm(),
      // 工程タイプ
      'process_type' => $process_type,
    ]);
  }
}
