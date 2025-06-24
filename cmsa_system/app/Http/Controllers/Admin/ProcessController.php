<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Process\Mode1Trait;
use App\Http\Controllers\Admin\Process\Mode2Trait;
use App\Http\Controllers\Admin\Process\Mode3Trait;
use App\Http\Controllers\Controller;
use App\Libs\CheckProject;
use App\Libs\projectParams;
use App\Models\Master\Unit;
use App\Models\ProcessTerm;
use App\Models\Process;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcessController extends Controller
{
  use Mode1Trait, Mode2Trait, Mode3Trait;

  // route/web.php
  protected string $redirect = 'admin.process.index';
  // resources/views/
  protected string $view = 'admin.process.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    $this->title = __('admin.process._menu');
  }

  /**
   * @param Request $request
   * @return View|RedirectResponse
   */
  public function index(Request $request): View|RedirectResponse
  {
    $pp = new projectParams();
    $mode_num = $pp->nowModeNum();

    if($mode_num==2) {
      return redirect()->route('admin.csv.index');
    }

    // フリーフォームIDをセッションに追加
    if($request->has('free_form_id')) {
      $pp->putFreeForm($request->get('free_form_id'));
    }
    $free_form = $pp->nowFreeForm();

    // 工程が存在しない場合
    if($free_form->processes->isEmpty()) {
      foreach (__('array.process_type.params') as $num => $label) {
        $items = [
          'free_form_id' => $free_form->id,
          'process_type' => $num,
        ];
        Process::create($items);
      }
    }

    // 工程の作成直後はリレーションで取得できない
    $processes = Process::where('free_form_id', $free_form->id)->get();

    if($mode_num==3) {
      $processes = $processes->filter(function($process) {
        return $process->process_terms->isNotEmpty();
      });
    }

    $items = [
      'data' => $processes,
      // モード
      'mode_num' => $mode_num,
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * edit3コメントからedit2編集へ
   *
   * @param $type
   * @param $term
   * @return RedirectResponse
   */
  public function change($type, $term): RedirectResponse
  {
    $pp = new projectParams();
    $pp->putModeNum(2);
    return redirect()->route('admin.process.edit', ['process' . $type,  $term]);
  }

  /**
   * @param Request $request
   * @param $type
   * @param ProcessTerm|null $term
   * @return View|RedirectResponse
   */
  public function edit(Request $request, $type, ProcessTerm $term = null): View|RedirectResponse
  {
    $pp = new projectParams();

    // フリーフォームIDをセッションに追加
    if($request->has('free_form_id')) {
      $pp->putFreeForm($request->get('free_form_id'));
    }

    $mode_num = $pp->nowModeNum();

    // 選択状態を確認
    $cp = new CheckProject('processEdit');
    $result = $cp->check(term: $term);
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    // 工程タイプ取得
    if($type == 'all') {
      if($mode_num==1) {
        $process_type = $type;
      } else {
        $process_type = 1;
      }
    } else {
      $process_type = substr($type, -1);
    }

    // 通常
    $view = $this->view . 'edit' . $mode_num;

    // シミュレーション
    $view_type = 0;
    if($request->has('view_type')) {
      $view_type = $request->get('view_type');
    }
    if($view_type) {
      $view = $this->view . 'chart';
    }

    $items = [];
    switch($mode_num) {
      case 1:
        $items = $this->getItems1($process_type);
        break;
      case 2:
        $items = $this->getItems2($process_type, $term);
        break;
      case 3:
        $items = $this->getItems3($process_type);
        break;
    }
    // 単位
    $items['units'] = Unit::where('is_custom', 1)->orderBy('sequence')->pluck('title', 'id')->toArray();

    return view($view, $items);
  }

  /**
   * @param Request $request
   * @param Process $process
   * @return RedirectResponse
   */
  public function update(Request $request, Process $process): RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('process');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    // モード
    $mode_num = $request->get('mode_num');

    // 工程タイプ
    $process_type = $request->get('process_type');

    $route_params = [
      'type' => $process_type == 'all' ? $process_type : 'process' . $process_type,
    ];

    $alerts = [];
    switch($mode_num) {
      case 1:
        $alerts = $this->update1($request, $process);
        break;
      case 2:
        $this->update2($request);
        $route_params['term'] = $request->get('process_term_id');
        break;
      case 3:
        $this->update3($request, $process);
        break;
    }

    $next_process_type = 0;
    if($request->has('next_process_type')) {
      $next_process_type = $request->get('next_process_type');
    }

    if($mode_num == 1 && $process_type == 'all') {
      $label = '全工程費用項目';
    } elseif($mode_num == 1 && $next_process_type) {
      $route_params['type'] = 'process' . $next_process_type;
      $label = $process->processLabel;
    } elseif($mode_num == 2) {
      $change_mode = $request->get('is_mode_change');
      if($change_mode==3) {
        $pp = new projectParams();
        $pp->putModeNum(3);
      }
      $label = $process->processLabel;
    } else {
      $label = $process->processLabel;
    }

    // メッセージ
    $msg_type = 'msg_success';
    $message = $label . __('common.update_comment');
    if(!empty($alerts)) {
      $msg_type = 'msg_err';
      $message = implode('<br>', $alerts);
    }
    return redirect()
      ->route('admin.process.edit', $route_params)
      ->with($msg_type, $message);
  }
}
