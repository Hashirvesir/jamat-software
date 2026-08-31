<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Hook from AuthenticatesUsers, called right after a successful login.
     */
    protected function authenticated(Request $request, $user)
    {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Logged in',
        ]);
    }

    /**
     * Overrides AuthenticatesUsers::logout() because the trait's version calls
     * guard()->logout() first, which clears $request->user() before its own
     * loggedOut() hook runs — so the user id must be captured here instead.
     */
    public function logout(Request $request)
    {
        $userId = $request->user()?->id;

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userId) {
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'Logged out',
            ]);
        }

        return $request->wantsJson()
            ? response()->json([], 204)
            : redirect('/');
    }
}
