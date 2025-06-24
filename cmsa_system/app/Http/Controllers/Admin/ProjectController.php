<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\projectParams;
use App\Models\Master\Location;
use App\Models\Project;
use App\Models\User\User;
use Exception;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.index';
  // resources/views/
  protected string $view = 'admin.project.';
  // resources/lang/ja/admin.php
  protected $title;
  protected $user_ids = [];

  public function __construct()
  {
    $this->title = __('admin.project._menu');
  }

  /**
   * @return View
   */
  public function index(): View
  {
    $query = Project::query();

    // 現場ユーザー
    $user = Auth::user();
    if($user->hasRole('location_admin') || $user->hasRole('works_director')) {
      $query->where('location_id', $user->location_id);
    } elseif($user->hasRole('field_user')) {
      $query->where(function ($query) use($user) {
        $query->orWhere('field_user1_id', $user->id)
          ->orWhere('field_user2_id', $user->id)
          ->orWhere('field_user3_id', $user->id);
      });
    }
    $load = [];
    $data = $query->with($load)
      ->orderBy('id', 'desc')
      ->get();

    $pp = new projectParams();
    $items = [
      'data' => $data,
      // 現在選択中のプロジェクト
      'now_project_id' => $pp->nowProjectID(),
    ];
    return view('admin.index', $items);
  }

  /**
   * @return View
   */
  public function create(): View
  {
    $query = Location::where('invalid', 0);

    $user = Auth::user();
    if($user->hasRole(['location_admin'])) {
      $query->where('location_id', $user->location_id);
    }
    $locations = $query->pluck('title', 'id')->toArray();
    $items = [
      'locations' => $locations,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    $project = Project::create($request->all());

    // プロジェクトIDをセッションに追加
    $pp = new projectParams();
    $pp->forgetFreeForm();
    $pp->putProject($project->id);

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route('admin.free_form.index')
      ->with('msg_success', $message);
  }

  /**
   * @param Project $project
   * @return View
   */
  public function edit(Project $project): View
  {
    $query = Location::where('invalid', 0);

    $user = Auth::user();
    if($user->hasRole(['location_admin'])) {
      $query->where('location_id', $user->location_id);
    }
    $locations = $query->pluck('title', 'id')->toArray();

    $items = [
      'datum' => $project,
      'locations' => $locations,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param Project $project
   * @return RedirectResponse
   */
  public function update(Request $request, Project $project): RedirectResponse
  {
    $project->update($request->all());

    // プロジェクトIDをセッションに追加
    $pp = new projectParams();
    $pp->putProject($project->id);

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route('admin.free_form.index')
      ->with('msg_success', $message);
  }

  /**
   * @param Project $project
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(Project $project): RedirectResponse
  {
    $project->deleted_by = Auth::id();
    $project->save();
    $project->delete();

    // セッションリセット
    $pp = new projectParams();
    $pp->forgetAll();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @return RedirectResponse
   */
  public function reset(): RedirectResponse
  {
    // セッションリセット
    $pp = new projectParams();
    $pp->forgetAll();

    $message = '選択をリセットしました';
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * ［非同期］ユーザーリスト取得
   * @param Request $request
   * @return JsonResponse
   */
  public function getUser(Request $request): JsonResponse
  {
    $location_id = $request->get('location_id');
    $user_id = $request->get('user_id');
    foreach (range(1, 3) as $num) {
      ${'field_user' . $num . '_id'} = $request->get('field_user' . $num. '_id');
    }

    $empty = '<option value="">' . __('common.empty') . '</option>';

    // 現場工事長
    $users = User::role(['location_admin', 'field_user'])
      ->where('invalid', 0)
      ->where('location_id', $location_id)
      ->get();
    $managers[] = $empty;
    foreach ($users as $user) {
      $selected = $user->id == $user_id ? ' selected' : '';
      $managers[] = '<option value="' . $user->id . '"' . $selected . '>' . $user->name . '</option>';
    }

    // 現場ユーザー
    $field_users = User::role(['field_user'])
      ->where('invalid', 0)
      ->where('location_id', $location_id)
      ->get();
    foreach (range(1, 3) as $num) {
      if(!isset($field_users[$num])) {
        $field[$num][] = $empty;
      }
      foreach ($field_users as $user) {
        $selected = $user->id == ${'field_user' . $num . '_id'} ? ' selected' : '';
        $field[$num][] = '<option value="' . $user->id . '"' . $selected . '>' . $user->name . '</option>';
      }
    }

    $items = [
      'manager' => [
        'isDisabled' => count($managers) > 1 ? 1 : 0,
        'html' => implode("\n", $managers)
      ],
      'fieldUser1' => [
        'isDisabled' => count($field[1]) > 1 ? 1 : 0,
        'html' => implode("\n", $field[1])
      ],
      'fieldUser2' => [
        'isDisabled' => count($field[2]) > 1 ? 1 : 0,
        'html' => implode("\n", $field[2])
      ],
      'fieldUser3' => [
        'isDisabled' => count($field[3]) > 1 ? 1 : 0,
        'html' => implode("\n", $field[3])
      ],
    ];

    return response()->json($items);
  }
}
