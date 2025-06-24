<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\Permission;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    // route/web.php
    protected string $redirect = 'admin.user.permission.index';
    // resources/views/
    protected string $view = 'admin.user.permission.';
    // resources/lang/ja/admin.php
    protected $title;

    public function __construct()
    {
        if(!Auth::user()->hasPermissionTo('permissions_control')) {
            abort(404);
        }
        $this->title = __('admin.user.permission._menu');
    }

    /**
     * Display a listing of Permission.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application {
        $loginUser = Auth::user();

        $query = Permission::query();
        if(!$loginUser->hasRole('system_admin')) {
            $query->where('is_only_system_admin', 0);
        }
        $items = [
            'data' => $query->with(
                'user_created_by',
                'user_updated_by',
                'user_deleted_by',
            )->orderBy('sequence')->get(),
        ];
        return view($this->view . 'index', $items);
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application {
        return view($this->view . 'create');
    }

    /**
     * Store a newly created Permission in storage.
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

        Permission::create($request->all());

        $message = $this->title . __('common.store_comment');
        return redirect()
            ->route($this->redirect)
            ->with('msg_success', $message);
    }

    /**
     * Show the form for creating a new Permission.
     *
     * @param Permission $permission
     * @return Factory|\Illuminate\View\View
     */
    public function show(Permission $permission): Factory|\Illuminate\View\View {
        $items = [
            'datum' => $permission,
        ];
        return view($this->view . 'show', $items);
    }

    /**
     * Show the form for editing Permission.
     *
     * @param Permission $permission
     * @return Factory|\Illuminate\View\View
     */
    public function edit(Permission $permission): Factory|\Illuminate\View\View {
        $items = [
            'datum' => $permission,
        ];
        return view($this->view . 'edit', $items);
    }

    /**
     * Update Permission in storage.
     *
     * @param Request $request
     * @param Permission $permission
     * @return RedirectResponse
     */
    public function update(Request $request, Permission $permission): RedirectResponse {
        $request->validate([
            'name' => 'required',
            'ja' => 'required',
            'sequence' => 'required|Integer',
        ]);

        $permission->update($request->all());

        $message = $this->title . __('common.update_comment');
        return redirect()
            ->route($this->redirect)
            ->with('msg_success', $message);
    }

    /**
     * Remove Permission from storage.
     *
     * @param Permission $permission
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Permission $permission): RedirectResponse {
        $permission->deleted_by = Auth::id();
        $permission->save();
        $permission->delete();

        $message = $this->title . __('common.destroy_comment');
        return redirect()
            ->route($this->redirect)
            ->with('msg_success', $message);
    }
}
