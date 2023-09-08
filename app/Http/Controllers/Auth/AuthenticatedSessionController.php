<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

      $gc = $this->checkCaptcha($request->gc);
      if(!$gc) {
        $this->destroy($request);
        return redirect()->back()->withErrors(['BLOCKED' => 'El recaptcha no es válido.']);
      }

        $request->authenticate();

        $res = $this->checkBlocked($request->email);
        if($res===TRUE) {
          //dd($res);
          $this->destroy($request);
          return redirect()->back()->withErrors(['BLOCKED' => 'Su cuenta está bloqueada, contáctese con el administrador de sistemas']);
        }
        else {
          $request->session()->regenerate();

          return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function checkBlocked($email) {
      $user = User::where('email', '=', $email)->first();
      return $user->status != 1;
    }

    private function checkCaptcha($gc) {
      $recaptcha_secret = env('RECAPTCHA_SECRET');
      $response         = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $gc);
      $response         = json_decode($response, true);
      return $response['success'];
    }
}
