<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Location;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LocationController extends Controller
{
  // route/web.php
  protected string $redirect = 'admin.master.location.index';
  // resources/views/
  protected string $view = 'admin.master.location.';
  // resources/lang/ja/admin.php
  protected $title;

  public function __construct()
  {
    $this->title = __('admin.master.location._menu');
  }

  /**
   * @return View
   */
  public function index(): View
  {
    $load = [
      'user_created_by',
      'user_updated_by',
      'user_deleted_by',
    ];
    $data = Location::with($load)
      ->orderBy('sequence')
      ->get();

    $items = [
      'data' => $data,
    ];
    return view($this->view . 'index', $items);
  }

  /**
   * @return View
   */
  public function create(): View
  {
    $items = [
      'sequence' => Location::max('sequence') + 10,
    ];
    return view($this->view . 'create', $items);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    Location::create($request->all());

    $message = $this->title . __('common.store_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param Location $location
   * @return View
   */
  public function edit(Location $location): View
  {
    $items = [
      'datum' => $location,
    ];
    return view($this->view . 'edit', $items);
  }

  /**
   * @param Request $request
   * @param Location $location
   * @return RedirectResponse
   */
  public function update(Request $request, Location $location): RedirectResponse
  {
    $location->update($request->all());

    $message = $this->title . __('common.update_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }

  /**
   * @param Location $location
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(Location $location): RedirectResponse
  {
    $location->deleted_by = Auth::id();
    $location->save();
    $location->delete();

    $message = $this->title . __('common.destroy_comment');
    return redirect()
      ->route($this->redirect)
      ->with('msg_success', $message);
  }
}
