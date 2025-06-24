<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
  // config/auth.phpのpasswords
  private string $broker = 'users';
  private string $type = 'admin.';

  /**
   * ログインからのリンクによるフォームの表示
   * @return View
   */
  public function index(): View
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

  /**
   * Handle an incoming password reset link request.
   *
   * @throws ValidationException
   */
  public function email(Request $request): RedirectResponse
  {
    $request->validate([
      'email' => ['required', 'email'],
    ]);

    // ユーザーにパスワードのリセット用リンクを送信
    // リンクの送信をしたら、応答を調べ、ユーザーに表示する必要があるメッセージを確認
    // 最後に、適切な返信を送付
    $status = Password::broker($this->broker)->sendResetLink(
      $request->only('email')
    );
    return $status == Password::RESET_LINK_SENT
      ? back()->with('status', __($status))
      : back()->withInput($request->only('email'))
        ->withErrors(['email' => __($status)]);
  }

  /**
   * メール記載のメールアドレスからのフォームの表示
   */
  public function create($token): View
  {
    $items = [
      'token' => $token
    ];
    return view('admin.auth.reset_password', $items);
  }

  /**
   * フォームによるリセット
   *
   * @throws ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'token' => ['required'],
      'email' => ['required', 'email'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Here we will attempt to reset the user's password. If it is successful we
    // will update the password on an actual user model and persist it to the
    // database. Otherwise we will parse the error and return the response.
    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user) use ($request) {
        $user->forceFill([
          'password' => Hash::make($request->password),
          'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
      }
    );

    // If the password was successfully reset, we will redirect the user back to
    // the application's home authenticated view. If there is an error we can
    // redirect them back to where they came from with their error message.
    return $status == Password::PASSWORD_RESET
      ? redirect()->route('login')->with('status', __($status))
      : back()->withInput($request->only('email'))
        ->withErrors(['email' => __($status)]);
  }
}
