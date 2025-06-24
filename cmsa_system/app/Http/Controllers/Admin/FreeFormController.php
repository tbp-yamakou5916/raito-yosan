<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Calculator\FreeFormCalculator;
use App\Libs\CheckProject;
use App\Libs\projectParams;
use App\Models\FreeForm;
use App\Models\Master\FfCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FreeFormController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.free_form.index';
  // resources/views/
  protected string $view = 'admin.free_form.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    $this->title = __('admin.free_form._menu');
  }

  /**
   * @param Request $request
   * @return View|RedirectResponse
   */
  public function index(Request $request): View|RedirectResponse
  {
    // プロジェクトIDをセッションに追加
    $pp = new projectParams();
    if($request->has('project_id')) {
      $pp->forgetFreeForm();
      $pp->putProject($request->get('project_id'));
    }


    // データ取得
    $load = [];
    $query = FreeForm::query();
    // プロジェクトがある場合
    $now_project_id = $pp->nowProjectID();
    if($now_project_id) {
      $query->where('project_id', $now_project_id);
    }
    $data = $query->whereHas('project', fn($p) => $p)
      ->with($load)
      ->orderBy('updated_at', 'desc')
      ->get();

    $items = [
      'data' => $data,
      // 現在選択中のプロジェクト
      'now_project_id' => $now_project_id,
      // 現在選択中のフリーフォーム
      'now_free_form_id' => $pp->nowFreeFormID(),
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * @return View|RedirectResponse
   */
  public function create(): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('free_form');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    $pp = new projectParams();
    $now_project_id = $pp->nowProjectID();

    $items = [
      'project_id' => $now_project_id,
      'ff_categories' => FfCategory::selectArray2(),
      'frameWidths' => FfCategory::frameWidthArray(),
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('free_form');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    $res = FreeForm::where('title', $request->get('title'))
      ->where('ff_category_id', $request->get('ff_category_id'))
      ->first();
    if($res) {
      $message = '同じ「' . __('admin.free_form.title') . '」「' . __('admin.master.ff_category._menu') . '」が既に存在します';
      return back()->with('msg_err', $message);
    }

    $free_form = FreeForm::create($request->all());

    // フリーフォームIDをセッションに追加
    $pp = new projectParams();
    $pp->putFreeForm($free_form->id);

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route('admin.process.edit', 'all')
      ->with('msg_success', $message);
  }

  /**
   * @param FreeForm $free_form
   * @return View|RedirectResponse
   */
  public function edit(FreeForm $free_form): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('free_form');
    $result = $cp->check($free_form);
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    $items = [
      'datum' => $free_form,
      'ff_categories' => FfCategory::selectArray2(),
      'frameWidths' => FfCategory::frameWidthArray(),
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param FreeForm $free_form
   * @return RedirectResponse
   */
  public function update(Request $request, FreeForm $free_form): RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('free_form');
    $result = $cp->check($free_form);
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    $free_form->update($request->all());

    // フリーフォームIDをセッションに追加
    $pp = new projectParams();
    $pp->putFreeForm($free_form->id);

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route('admin.free_form.edit', $free_form->id)
      ->with('msg_success', $message);
  }

  /**
   * @param FreeForm $free_form
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(FreeForm $free_form): RedirectResponse
  {
    $free_form->deleted_by = Auth::id();
    $free_form->save();
    $free_form->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * ［非同期］計算結果取得
   * @param Request $request
   * @return JsonResponse
   */
  public function getResult(Request $request): JsonResponse
  {
    $params = [
      'ff_category_id' => $request->get('ffCategoryId'),
      'area' => $request->get('area'),
      'price' => $request->get('price'),
      'thickness' => $request->get('thickness'),
    ];
    $free_form = new FreeForm($params);

    $ffc = new FreeFormCalculator($free_form);

    return response()->json($ffc->getResult());
  }
}
