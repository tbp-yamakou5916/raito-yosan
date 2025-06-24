<?php

namespace App\Libs\File;

use App\Libs\projectParams;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CsvCommon {
  protected $request;
  // ステータス
  protected bool $status = true;
  // エラー情報
  protected $error = Null;
  // ファイル名
  protected $filename = Null;
  // SJISフラグ
  protected bool $is_sjis = true;
  // ファイル内容
  protected $fp = Null;
  // データ項目
  protected $column_names = Null;
  // データタイプ
  protected $type = Null;
  // メモリ使用量
  protected int $usage = 0;
  // メモリ最大使用量
  protected int $peak_usage = 0;

  /**
   * CsvCommon constructor.
   */
  public function __construct()
  {
    // php7だとこれが必要っぽい
    // これがないとfgetcsvがうまく動作しない
    /*
    if(0 === strpos(PHP_OS, 'WIN')) {
        setlocale(LC_CTYPE, 'C');
    }
    */
  }

  /**
   * ステータスを返す
   *
   * @return bool
   */
  public function status(): bool {
    return $this->status;
  }
  /**
   * エラー内容を返す
   *
   * @return mixed
   */
  public function error(): mixed {
    return $this->error;
  }
  /**
   * ファイル名を設定
   *
   * @param $filename
   *
   * @return void
   */
  public function setFilename($filename): void {
    $this->filename = $filename;
  }
  /**
   * メモリ使用量取得
   * @return string
   */
  public function memoryUsage(): string {
    return 'メモリ使用量：'. number_format($this->usage) . 'MB';
  }
  /**
   * メモリ最大使用量取得
   * @return string
   */
  public function memoryPeakUsage(): string {
    return 'メモリ最大使用量：'. number_format($this->peak_usage) . 'MB';
  }
  /**
   * 空行チェック
   * @param $line
   *
   * @return bool
   */
  public function checkNullLine($line): bool {
    foreach($line as $v) {
      if($v) return false;
    }
    return true;
  }
  /**
   * ファイルを確認
   *
   * @return bool
   */
  public function checkFile(): bool {
    // ファイルを取得
    $file = $this->request->file('file');
    $this->filename = $this->makeFileName($file);

    // アップロードファイルのバリデート
    $result = $this->validateUploadFile();
    if(!$result) return false;

    // CSVファイルをサーバーに保存
    $this->saveFile('upload');

    // ファイルを開く
    $result = $this->openFile('upload');
    if(!$result) return false;

    // 一行目（ヘッダ）の確認
    //$result = $this->checkHeader();
    //if(!$result) return false;

    return true;
  }

  public function makeFileName($file)
  {
    $pp = new projectParams();
    $p_id = $pp->nowProjectID();
    $ff_id = $pp->nowFreeFormID();
    $process_type = $this->request->get('process_type');

    $time = Carbon::now()->format('YmdHis');

    return $p_id . '/' . $ff_id . '/' . $process_type . '/' . $time . '_' . $file->getClientOriginalName();
  }

  /**
   * アップロードファイルのバリデート
   *
   * @return bool
   */
  public function validateUploadFile(): bool {
    $rules = [
      'file' => 'required|file',
      //'file' => 'required|file|mimetypes:text/plain|mimes:csv,txt',
    ];
    $messages = [
      'file.required'  => 'ファイルを選択してください',
      'file.file'      => 'ファイルアップロードに失敗しました',
      'file.mimetypes' => 'ファイル形式が不正です',
      'file.mimes'     => 'ファイル拡張子が異なります',
    ];

    $validator = Validator::make($this->request->all(), $rules, $messages);

    if ($validator->fails() === true) {
      $this->status = false;
      $this->error = $validator->errors()->first('file');
      return false;
    }

    return true;
  }

  /**
   * ファイルの保存
   *
   * @param $disk
   *
   * @return bool
   */
  public function saveFile($disk): bool {
    // ファイルを取得
    $file = $this->request->file('file');

    // ファイルを保存
    Storage::disk($disk)->putFileAs('', $file, $this->filename);

    return true;
  }

  /**
   * アップロードファイルのバリデート
   *
   * @param $disk
   *
   * @return bool
   */
  public function openFile($disk): bool {
    // ファイル内のテキストのUTF-8化
    /*
    stream_filter_register(
        'sjis_to_utf8_encoding_filter',
        SjisToUtf8EncodingFilter::class
    );
    */
    // ファイルパスを取得
    $path = Storage::disk($disk)->path($this->filename);

    // ファイルを開く
    $fp = fopen($path, 'r');
    //stream_filter_append($fp, 'sjis_to_utf8_encoding_filter');

    $this->status = true;
    $this->error = '';
    if(!$fp) {
      $this->status = false;
      $this->error = 'ファイルが開けませんでした';
      return false;
    }

    $this->fp = $fp;

    return true;
  }

  /**
   * ヘッダーのチェック
   *
   * @return bool
   */
  public function checkHeader(): bool {
    $errors = [];
    $fp = $this->fp;
    // 1行目を取得
    rewind($fp);
    $this_header = fgetcsv($fp);

    // ボム付きであれば、削除
    $bom = hex2bin('EFBBBF');
    if (preg_match("/^{$bom}/", $this_header[0])) {
      $this_header[0] = substr($this_header[0], 3);
      if(str_contains($this_header[0], '"')) {
        $this_header[0] = substr($this_header[0], 1, -1);
      }
    }

    // 文字コード確認
    // 先頭だけのチェックだとIDなど英字の場合がある
    $checks = [
      'UTF-8',
      'SJIS'
    ];
    foreach($this_header as $v) {
      $res = mb_detect_encoding($v, $checks, true);
      if(!$this->is_sjis && $res=='SJIS') {
        $this->is_sjis = true;
        break;
      }
    }

    // 改行コード削除 / エンコーディング
    foreach ($this_header as $num => $val) {
      $val = str_replace("\n", '', $val);
      if($this->is_sjis) $val = mb_convert_encoding($val, 'UTF-8', 'SJIS');
      $this_header[$num] = $val;
    }

    // エラー確認
    foreach($this->column_names as $i => $val) {
      if(!isset($this_header[$i])) {
        $errors[] = [
          'column' => $i + 1,
        ];
        break;
      }
      if($this_header[$i] != $val['csv']) {
        $errors[] = [
          'column' => $i + 1,
          'name' => $val['csv'],
          'err' => $this_header[$i]
        ];
      }
    }

    // 項目順が異なる場合のエラー表示
    if(count($errors)) {
      $error_text = '';
      foreach($errors as $err) {
        if(!isset($err['name'])) {
          $error_text .= $err['column'] . '列目が存在しませんでした' . PHP_EOL;
        } else {
          $error_text .= $err['column'] . '列目が「' . $err['name'] . '」ではなく「' . $err['err'] . '」でした' . PHP_EOL;
        }
      }
      $error_text .= '上記のため、保存ができませんでした';

      $this->status = false;
      $this->error = $error_text;
      return false;
    }

    return true;
  }

  /**
   * 空行チェック
   *
   * @return void
   */
  public function saveUsedMemory(): void {
    $this->usage = memory_get_usage() / (1024 * 1024);
    $this->peak_usage = memory_get_peak_usage() / (1024 * 1024);
  }
}
