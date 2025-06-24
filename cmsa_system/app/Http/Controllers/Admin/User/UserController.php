<?php

namespace App\Http\Controllers\Admin\User;

use App\Libs\SendMail;
use App\Models\Master\Location;
use App\Models\User\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.user.user.index';
  // resources/views/
  protected string $view = 'admin.user.user.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    if(!(Auth::user()->hasPermissionTo('users_control') || Auth::user()->hasPermissionTo('only_user_manage'))) {
      abort(404);
    }
    $this->title = __('admin.user.user._menu');
  }

  /**
   * @return View
   */
  public function index(): View
  {
    $loginUser = Auth::guard('user')->user();

    $query = User::query();
    // システム管理者以外
    if(!$loginUser->hasRole('system_admin')) {
      $query->whereHas( 'roles', function ($sql) {
        $sql->where('name', '!=', 'system_admin');
      });
      // サイト管理者以外
      if(!$loginUser->hasRole('site_admin')) {
        $query->whereHas( 'roles', function ($sql) {
          $sql->where('name', '!=', 'site_admin');
        });
        // 拠点管理者以外
        if(!$loginUser->hasRole('location_admin')) {
          $query->whereHas( 'roles', function ($sql) {
            $sql->where('name', '!=', 'location_admin');
          });
        }
      }
    }
    $loads = [
      'roles',
      'user_created_by',
      'user_updated_by',
      'user_deleted_by',
    ];
    $data = $query->with($loads)->get();
    $items = [
      'data' => $data,
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * Show the form for creating new User.
   *
   * @return View
   */
  public function create(): View
  {
    list($roles, $locations) = $this->getItems();

    $items = [
      'locations' => $locations,
      'roles' => $roles,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * Store a newly created User in storage.
   *
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required',
      'roles' => 'required'
    ]);

    // データ作成
    $user = User::create($request->except('roles', '_token'));

    // 権限追加
    $roles = $request->input('roles') ? $request->input('roles') : [];
    $roles = Role::whereIn('id', $roles)->get();
    $user->assignRole($roles);

    // メール送信
    $mail = new SendMail();
    $items = [
      'isCustomer' => true,
      'category' => 'user',
      'lang' => 'user',
      'model' => $user,
    ];
    $mail->setParams($items);

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param User $user
   * @return View
   */
  public function show(User $user): View
  {
    $user->load('roles');

    $items = [
      'datum' => $user,
    ];
    return view($this->view . 'show', $items);
  }

  /**
   * @param User $user
   * @return View
   */
  public function edit(User $user): View
  {
    list($roles, $locations) = $this->getItems();

    $items = [
      'datum' => $user,
      'locations' => $locations,
      'roles' => $roles,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * Update the specified User in storage.
   *
   * @param Request $request
   * @param User $user
   * @return RedirectResponse
   */
  public function update(Request $request, User $user): RedirectResponse {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users,email,' . $user->id . ',id',
      'roles' => 'required',
    ]);

    // パスワードの更新
    $password = $user->password;
    if($request->filled('password')) {
      $password = $request->get('password');
    }
    $request->merge(['password' => $password]);

    // データ更新
    $user->update($request->except('roles', '_token'));

    // 権限更新
    $roles = [];
    if($request->has('roles')) {
      $roles = $request->get('roles');
    }
    $roles = Role::whereIn('id', $roles)->get();
    $user->syncRoles($roles);

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * Remove the specified User from storage.
   *
   * @param User $user
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(User $user): RedirectResponse {
    $user->deleted_by = Auth::id();
    $user->save();
    $user->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * 権限 / 拠点取得
   *
   * @return array
   */
  private function getItems()
  {
    $loginUser = Auth::user();

    // 権限
    $query = Role::where('invalid', 0);
    // システム管理者以外
    $ignores = [];
    if(!$loginUser->hasRole('system_admin')) {
      $ignores[] = 'system_admin';
      // サイト管理者以外
      if(!$loginUser->hasRole('site_admin')) {
        $ignores[] = 'site_admin';
        // 拠点管理者以外
        if(!$loginUser->hasRole('location_admin')) {
          $ignores[] = 'location_admin';
        }
      }
    }
    $query->whereNotIn('name', $ignores);
    $roles = $query->pluck('ja', 'id');

    // 拠点
    $query = Location::where('invalid', 0);
    if($loginUser->hasRole('location_admin')) {
      $query->where('id', $loginUser->location_id);
    }
    $locations = $query->pluck('title', 'id')->toArray();
    if($loginUser->hasRole('system_admin') || $loginUser->hasRole('system_admin')) {
      $locations = __('admin.user.user.all_location.select') + $locations;
    }

    return [$roles, $locations];
  }
}
