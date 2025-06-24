<?php

namespace App\Libs;

use Illuminate\Support\Facades\Storage;

class CheckImage {
    protected array $params = [];
    protected mixed $disk = '';
    protected mixed $images;
    protected mixed $files;

    /**
     * @param $request
     * @param $params
     *
     * パスにIDを指定したい場合
     * $params = [
     *   'id' => (number),
     * ]
     * 'column'は利用法不明
     */
    public function __construct($request, $params = []) {
        $this->params = $params;
        $this->disk = $request->input('disk');
        $this->images = $request->input('images');
        $this->files = $request->file('files');
        if (empty($this->images)) {
            return [];
        }
    }

    /**
     * 全画像の保存
     *
     * @return array
     */
    public function check(): array {
        $arr = [];
        foreach($this->images as $sequence => $items) {
            $file = $this->files[$sequence] ?? Null;

            // 既存ファイルがある場合
            if($items['img_path']) {
                $arr[$sequence] = [
                    'path' => $items['img_path'],
                    'caption' => $items['caption'] ?? Null,
                    'sequence' => $sequence,
                ];
                if (!empty($this->params)) {
                    $arr[$sequence][$this->params['column']] = $this->params['id'];
                }
                // 削除、又は、新規ファイルがある場合
                if($items['is_del'] || $file) {
                    Storage::disk($this->disk)->delete($items['img_path']);
                    unset($arr[$sequence]);
                }
            }

            // 画像あり
            if($file) {
                // 画像の保存
                $path = $this->params['id'] ?? Null;
                $arr[$sequence] = [
                    'path' => Storage::disk($this->disk)->putFile($path, $file),
                    'caption' => $items['caption'] ?? Null,
                    'sequence' => $sequence,
                ];
                if (isset($this->params['column'])) {
                    $arr[$sequence][$this->params['column']] = $this->params['id'];
                }
            }
        }
        return $arr;
    }
}
