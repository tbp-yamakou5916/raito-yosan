<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
  private string $guard = 'user';
  private string $type = 'admin.';

  /**
   * Display the login view.
   */
  public function showLoginForm(): View
  {
    return view($this->type . 'auth.login');
  }

  /**
   * Handle an incoming authentication request.
   */
  public function login(LoginRequest $request): RedirectResponse
  {
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(route($this->type . 'index', absolute: false));
  }

  /**
   * Destroy an authenticated session.
   */
  public function logout(Request $request): RedirectResponse
  {
    Auth::guard($this->guard)->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route($this->type . 'login');
  }
}
