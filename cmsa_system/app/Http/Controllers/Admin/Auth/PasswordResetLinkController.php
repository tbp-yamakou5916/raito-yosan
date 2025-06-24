<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
  // config/auth.phpのpasswords
  private string $broker = 'users';
  private string $type = 'admin.';
  /**
   * Display the password reset link request view.
   */
  public function create(): View
  {
    $current = Route::current()->getName();
    $type = 'forgot_password';
    if(str_contains($current, 'change-password')) {
      $type = 'change_password';
    }
    $items = [
      'type' => $type
    ];
    return view($this->type . 'auth.forgot_password', $items);
  }
}
