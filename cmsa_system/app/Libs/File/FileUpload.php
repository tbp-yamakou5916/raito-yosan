<?php

namespace App\Libs\File;

use App\Libs\projectParams;
use App\Models\Process;
use App\Models\ProcessTerm;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FileUpload extends CsvCommon {

  /**
   * FileUpload constructor.
   *
   * @param $request
   */
  public function __construct($request = Null)
  {
    parent::__construct();

    if($request) {
      $this->request = $request;
    }
  }

  /**
   * FPの取得
   *
   * @return mixed|null
   */
  public function getFP(): mixed {
    return $this->fp;
  }

  /**
   * ファイルのアップロード
   *
   * @return int
   */
  public function upload(): int
  {
    // ファイルを確認
    $res = $this->checkFile();

    // ファイル形式不明
    if(!$res) return 2;

    $fp = $this->fp;
    rewind($fp);

    // タイトル行取得
    $header = fgetcsv($fp);

    $items = [
      'start' => null,
      'total_length' => null,
      'length_within' => 0,
      'total_length_after' => 0,
      'total_area' => 0,
      'area_within' => 0,
      'total_area_after' => 0,
      'rate' => 0,
      'overall_rate' => 0,
    ];
    while ($line = fgetcsv($fp)) {
      // 空行チェック
      if($this->checkNullLine($line)) continue;

      // エンコーディング
      if($this->is_sjis) $line = mb_convert_encoding($line, 'UTF-8', 'SJIS');

      if(str_starts_with($line[0], '施工日')) {
        if(str_contains($line[1], '〜')) {
          $arr = explode('〜', $line[1]);
          $items['start'] = Carbon::parse($arr[0]);
          $items['end'] = Carbon::parse($arr[1]);
        } else {
          $items['start'] = Carbon::parse($line[1]);
          $items['end'] = Carbon::parse($line[1]);
        }
      } elseif(str_starts_with($line[0], '総延長')) {
        $items['total_length'] = $line[1];
      } elseif(str_starts_with($line[0], '範囲内延長')) {
        $items['length_within'] = $line[1];
      } elseif(str_starts_with($line[0], '施工終了総延長')) {
        $items['total_length_after'] = $line[1];
      } elseif(str_starts_with($line[0], '総面積')) {
        $items['total_area'] = $line[1];
      } elseif(str_starts_with($line[0], '範囲内面積')) {
        $items['area_within'] = $line[1];
      } elseif(str_starts_with($line[0], '施工終了総面積')) {
        $items['total_area_after'] = $line[1];
      } elseif(str_starts_with($line[0], '進捗率')) {
        $items['rate'] = $line[1];
      } elseif(str_starts_with($line[0], '全体進捗率')) {
        $items['overall_rate'] = $line[1];
      } elseif(str_starts_with($line[0], '交点数')) {
        // 未利用（念のため）
        $items['intersection'] = $line[1];
      }
    }

    $pp = new projectParams();

    if($items['start'] && $items['end']) {
      // 既存データの確認
      $free_form_id = $pp->nowFreeFormID();
      $process_type = $this->request->get('process_type');

      // 工程
      $process = Process::where('free_form_id', $free_form_id)
        ->where('process_type', $process_type)
        ->first();

      // 施工日違いは別データとして登録
      $process_term = ProcessTerm::where('process_id', $process->id)
        ->whereDate('start', $items['start'])
        ->whereDate('end', $items['end'])
        ->first();

      if($process_term) {
        $items['updated_by'] = Auth::id();
        $process_term->update($items);
      } else {
        $process = Process::where('free_form_id', $free_form_id)
          ->where('process_type', $process_type)
          ->first();
        $items['free_form_id'] = $free_form_id;
        $items['process_id'] = $process->id;
        $items['created_by'] = Auth::id();
        ProcessTerm::create($items);
      }
      return 1;
    } elseif(empty($items)) {
      // ファイル形式不明
      return 2;
    } else {
      // 施工日必須
      return 3;
    }
  }
}
