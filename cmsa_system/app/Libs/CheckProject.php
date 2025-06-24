<?php

namespace App\Libs;

class CheckProject {

  private $type;

  /**
   * @param $type
   */
  public function __construct($type) {
    // free_form
    // process
    // delivery
    // csv
    $this->type = $type;
  }

  /**
   * 選択状態を確認
   *
   * @param null $free_form
   * @param null $term
   * @return array
   */
  public function check($free_form = null, $term = null): array {
    $pp = new projectParams();

    // free_formのみ
    if($this->type=='free_form') {
      // Bookmarkなどに対応
      if($free_form && !$pp->nowProjectID()) {
        $pp->putProject($free_form->project_id);
      }
      if($free_form && !$pp->nowFreeFormID()) {
        $pp->putFreeForm($free_form->id);
      }
    }

    $is_error = false;
    $message = null;
    $redirect = null;

    // free_form以外
    if($this->type != 'free_form' && !$pp->nowFreeFormID()) {
      $is_error = true;
      $message = __('admin.free_form.title2') . 'を選択してください';
      $redirect = route('admin.free_form.index');
    }

    if(!$pp->nowProjectID()) {
      $is_error = true;
      $message = __('admin.project.title2') . '又は' . __('admin.free_form.title2') . 'を選択してください';
      $redirect = route('admin.index');
    }

    // STEP2以外
    if($pp->nowModeNum()!=2 && in_array($this->type, ['delivery', 'csv'])) {
      $is_error = true;
      $message = '';
      if($pp->nowModeNum()==1) {
        $redirect = route('admin.process.edit', 'all');
      } else {
        $redirect = route('admin.process.edit', 'process1');
      }
    }

    // STEP2
    if($pp->nowModeNum()==2 && $this->type=='processEdit' && !$term) {
      $is_error = true;
      $message = '';
      $redirect = route('admin.csv.index');
    }

    // シミュレーション
    if($this->type=='chart' && $pp->nowModeNum()!=3) {
      $is_error = true;
      $message = '';
      if($pp->nowModeNum()==1) {
        $redirect = route('admin.process.edit', 'all');
      } else {
        $redirect = route('admin.csv.index');
      }
    }

    return [
      'is_error' => $is_error,
      'message' => $message,
      'redirect' => $redirect,
    ];
  }
}
