<?php

namespace App\Libs;

use App\Models\FreeForm;
use App\Models\Project;
use Illuminate\Support\Facades\Session;

class projectParams {

  // モード
  protected string $mode_session = 'mode';
  protected $mode_num = 1;

  // プロジェクト
  protected string $project_session = 'project';
  protected $project_id = null;
  protected $project = null;

  // フリーフォーム
  protected string $free_form_session = 'free_form';
  protected $free_form_id = null;
  protected $free_form = null;

  public function __construct()
  {
    // プロジェクト 初期設定
    $this->initProject();
  }

  /**
   * プロジェクト 初期設定
   * @return void
   */
  private function initProject(): void
  {
    // モード
    $this->mode_num = Session::get($this->mode_session);
    if(!$this->mode_num) {
      $this->mode_num = 1;
    }

    // フリーフォーム
    $this->free_form_id = Session::get($this->free_form_session);
    $now_free_form = Null;
    if($this->free_form_id) {
      $now_free_form = FreeForm::find($this->free_form_id);
    }

    // プロジェクト
    $this->project_id = Session::get($this->project_session);
    $now_project = null;
    if($now_free_form) {
      if($this->project_id) {
        if($now_free_form->project_id == $this->project_id) {
          $now_project = Project::find($this->project_id);
        } else {
          // プロジェクト設定
          // initProjectされる
          $this->putProject($now_free_form->project_id);
        }
      } else {
        // プロジェクト設定
        // initProjectされる
        $this->putProject($now_free_form->project_id);
      }
    } elseif($this->project_id) {
      $now_project = Project::find($this->project_id);
    }

    $this->project = $now_project;
    $this->free_form = $now_free_form;
  }

  /**
   * 現在のモード番号
   *
   * @return int|mixed
   */
  public function nowModeNum(): mixed
  {
    return $this->mode_num;
  }

  /**
   * 現在選択中のプロジェクトID
   *
   * @return int|mixed
   */
  public function nowProjectID(): mixed
  {
    return $this->project_id;
  }

  /**
   * 現在選択中のフリーフォームID
   *
   * @return int|mixed
   */
  public function nowFreeFormID(): mixed
  {
    return $this->free_form_id;
  }

  /**
   * 現在選択中のプロジェクトモデル
   *
   * @return Project|null
   */
  public function nowProject(): ?Project
  {
    return $this->project;
  }

  /**
   * 現在選択中のフリーフォームモデル
   *
   * @return FreeForm|null
   */
  public function nowFreeForm(): ?FreeForm
  {
    return $this->free_form;
  }

  /**
   * モード セッション保存
   *
   * @param $mode_num
   *
   * @return void
   */
  public function putModeNum($mode_num): void
  {
    Session::put($this->mode_session, $mode_num);
    // プロジェクト 初期設定
    $this->initProject();
  }

  /**
   * プロジェクト セッション保存
   *
   * @param $project_id
   *
   * @return void
   */
  public function putProject($project_id): void
  {
    Session::put($this->project_session, $project_id);
    // プロジェクト 初期設定
    $this->initProject();
  }

  /**
   * フリーフォーム セッション保存
   *
   * @param $free_form_id
   *
   * @return void
   */
  public function putFreeForm($free_form_id): void
  {
    Session::put($this->free_form_session, $free_form_id);
    // プロジェクト 初期設定
    $this->initProject();
  }

  /**
   * プロジェクト セッションチェック
   *
   * @param $param
   *
   * @return void
   */
  public function checkProjectId($param): void
  {
    if(is_array($param)) {
      if(in_array($this->project_id, $param)) {
        Session::forget($this->project_session);
      }
    } else {
      if($this->project_id == $param) {
        Session::forget($this->project_session);
      }
    }
  }

  /**
   * プロジェクト セッションリセット
   *
   * @return void
   */
  public function forgetAll(): void
  {
    Session::forget($this->project_session);
    $this->project_id = null;
    $this->project = null;

    Session::forget($this->free_form_session);
    $this->free_form_id = null;
    $this->free_form = null;

    Session::forget($this->mode_session);
    $this->mode_num = 1;
  }

  /**
   * プロジェクト セッションリセット
   *
   * @return void
   */
  public function forgetFreeForm(): void
  {
    Session::forget($this->free_form_session);
    $this->free_form_id = null;
    $this->free_form = null;
  }
}
