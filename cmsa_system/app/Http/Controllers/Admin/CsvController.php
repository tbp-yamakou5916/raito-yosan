<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\CheckProject;
use App\Libs\File\FileUpload;
use App\Libs\projectParams;
use App\Models\Process;
use App\Models\ProcessTerm;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CsvController extends Controller
{
  // route/web.php
  protected $redirect = 'admin.csv.index';
  // resources/views/
  protected $view = 'admin.csv.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    // タイムアウトの時間をなしに
    set_time_limit( 0 );
    ini_set("max_execution_time",0);

    $this->title = __('admin.csv._menu');
  }

  /**
   * @param Request $request
   * @return Renderable|RedirectResponse
   */
  public function index(Request $request): Renderable|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('csv');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    $pp = new projectParams();
    $free_form_id = $pp->nowFreeFormID();

    $query = ProcessTerm::where('free_form_id', $free_form_id);

    // 日付選択
    $date = null;
    if($request->filled('date')) {
      $date = $request->get('date');
      $arr = explode(',', $date);
      $query = $query->where('start', $arr[0])->where('end', $arr[1]);
    }
    // 工程選択
    $process_type = null;
    if($request->filled('process_type')) {
      $process_type = $request->get('process_type');
      $query = $query->whereHas('process', fn($q) => $q->where('process_type', $process_type));
    }

    $data = $query->orderBy('end', 'desc')->get();

    // セレクトメニュー用配列
    $base = ProcessTerm::where('free_form_id', $free_form_id)->get();

    $dates = $base
      ->unique('constructionTermLabel')
      ->map(function($item) {
      return [
        'value' => $item->startValue . ',' . $item->endValue,
        'label' => $item->constructionTermLabel,
      ];
    })->pluck('label', 'value')->toArray();

    $processes = $base
      ->unique('processLabel')
      ->map(function($item) {
        return [
          'value' => $item->process_type,
          'label' => $item->processLabel,
        ];
      })->pluck('label', 'value')->toArray();

    // 新規作業実績登録 工程選択
    // 準備・撤去工 / 法面清掃工 / 場内清掃工;
    $params = __('array.process_type.params');
    $buttons = [];
    $options = [];
    foreach($params as $key => $label) {
      if(in_array($key, [1, 2, 7])) {
        $buttons[$key] = $label;
      } else {
        $options[$key] = $label;
      }
    }


    $items = [
      'data' => $data,
      // 工程選択
      'options' => $options,
      'buttons' => $buttons,
      // 全面積完了
      'free_form_id' => $free_form_id,
      // 日付選択
      'date' => $date,
      'dates' => $dates,
      // 日付選択
      'process_type' => $process_type,
      'processes' => $processes,
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * @param $process_type
   * @return View|RedirectResponse
   */
  public function create($process_type): View|RedirectResponse
  {
    // プロジェクトクラス
    $pp = new projectParams();

    // フリーフォーム取得
    $free_form = $pp->nowFreeForm();

    $items = [
      'free_form_id' => $free_form->id,
      'process_type' => $process_type,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    $free_form_id = $request->get('free_form_id');
    $process_type = $request->get('process_type');
    $process = Process::where('free_form_id', $free_form_id)
      ->where('process_type', $process_type)
      ->first();
    $start = $request->get('start');
    $end = $request->get('end');
    $created_by = $request->get('created_by');

    // ① 面積（㎡）
    $area = $process->free_form->area;

    $items = [
      'free_form_id' => $free_form_id,
      'process_id' => $process->id,
      'start' => $start,
      'end' => $end,
      'created_by' => $created_by,

      'total_length' => 0,
      'total_area' => $area,
      'length_within' => 0,
      'area_within' => $area,
      'rate' => 100,
      'total_length_after' => 0,
      'total_area_after' => $area,
      'overall_rate' => 100,
    ];

    ProcessTerm::create($items);

    $message = $process->processLabel . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * ファイルアップロード
   * @param Request $request
   *
   * @return RedirectResponse
   * @throws Exception
   */
  public function upload(Request $request): RedirectResponse
  {
    $file_upload = new FileUpload($request);
    $num = $file_upload->upload();

    switch($num) {
      case 2:
        $type = 'msg_err';
        $message = 'ファイル形式が異なっています';
        break;
      case 3:
        $type = 'msg_err';
        $message = '施工日の開始日及び終了日は必須です';
        break;
      default:
        $type = 'msg_success';
        $message = 'ファイルをアップロードしました';
    }
//    $message .= PHP_EOL . $file_upload->memoryUsage();
//    $message .= PHP_EOL . $file_upload->memoryPeakUsage();

    return redirect($request->get('redirect'))
      ->with($type, $message);
  }

  /**
   * @param ProcessTerm $process_term
   * @return RedirectResponse
   */
  public function destroy(ProcessTerm $process_term): RedirectResponse
  {
    $process_term->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }
}
