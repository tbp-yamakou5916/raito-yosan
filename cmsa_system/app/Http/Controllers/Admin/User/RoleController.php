<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\Role;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.user.role.index';
  // resources/views/
  protected string $view = 'admin.user.role.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    if(!Auth::user()->hasPermissionTo('roles_control')) {
      abort(404);
    }
    $this->title = __('admin.user.role._menu');
  }

  /**
   * Display a listing of Role.
   *
   * @return Application|Factory|View
   */
  public function index(): View|Factory|Application {
    $loginUser = Auth::user();

    $query = Role::query();
    if(!$loginUser->hasRole('system_admin')) {
      $query->where('name', '!=', 'system_admin');
    }
    $items = [
      'data' => $query->with(
        'permissions',
        'user_created_by',
        'user_updated_by',
        'user_deleted_by',
      )->orderBy('sequence')->get(),
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * Show the form for creating new Role.
   *
   * @return Application|Factory|View
   */
  public function create(): View|Factory|Application {
    $loginUser = Auth::user();

    $query = Permission::query();
    if(!$loginUser->hasRole('system_admin')) {
      $query->whereNotIn('name', config('const.common.except_permission'));
      $query->where('is_only_system_admin', 0);
    }

    $select = [
      'permission' => $query->orderBy('sequence')->pluck('ja', 'name')
    ];
    $items = [
      'select' => $select,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * Store a newly created Role in storage.
   *
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse {
    $request->validate([
      'name' => 'required',
      'ja' => 'required',
      'sequence' => 'required|Integer',
    ]);

    $role = Role::create($request->except('permissions'));

    $permissions = [];
    if($request->has('permissions')) {
      $permissions = $request->input('permissions');
    }
    $permissions = Permission::whereIn('name', $permissions)->get();
    $role->givePermissionTo($permissions);

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * Show the form for creating a new Role.
   *
   * @param Role $role
   * @return Factory|\Illuminate\View\View
   */
  public function show(Role $role): Factory|\Illuminate\View\View {
    $items = [
      'datum' => $role->load('permissions'),
    ];
    return view($this->view . 'show', $items);
  }

  /**
   * Show the form for editing Role.
   *
   * @param Role $role
   * @return Factory|\Illuminate\View\View
   */
  public function edit(Role $role): Factory|\Illuminate\View\View {
    $loginUser = Auth::user();

    $query = Permission::query();
    if(!$loginUser->hasRole('system_admin')) {
      $query->whereNotIn('name', ['permission_create', 'permission_edit', 'permission_delete']);
      $query->where('is_only_system_admin', 0);
    }
    $select = [
      'permission' => $query->orderBy('sequence')->pluck('ja', 'name')
    ];
    $items = [
      'datum' => $role,
      'select' => $select,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * Update Role in storage.
   *
   * @param Request $request
   * @param Role $role
   * @return RedirectResponse
   */
  public function update(Request $request, Role $role): RedirectResponse {
    $request->validate([
      'name' => 'required',
      'ja' => 'required',
      'sequence' => 'required|Integer',
    ]);

    $role->update($request->except('permissions'));

    $permissions = [];
    if($request->has('permissions')) {
      $permissions = $request->input('permissions');
    }
    $role->syncPermissions($permissions);

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * Remove Role from storage.
   *
   * @param Role $role
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(Role $role): RedirectResponse {
    $role->deleted_by = Auth::id();
    $role->save();
    $role->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }
}
