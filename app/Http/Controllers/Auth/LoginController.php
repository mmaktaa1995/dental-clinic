<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * The maximum number of attempts to allow.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * The number of minutes to throttle for.
     *
     * @var int
     */
    protected $decayMinutes = 15;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'user']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

//        if ($response = $this->loggedOut($request)) {
//            return $response;
//        }

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    protected function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user has too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }

        // Reset login attempts on successful login
        $this->clearLoginAttempts($request);
        
        // Load roles with their permissions
        $user->load(['roles.permissions']);

        return $this->sendLoginResponse($request, $user);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->load(['roles.permissions']);
        
        return new UserResource($user);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ])->status(429);
    }

    protected function sendLoginResponse(Request $request, $user)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $user);
    }

    protected function authenticated(Request $request, $user)
    {
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'access_token' => explode('|', $user->createToken('personal-access-token')->plainTextToken)[1],
                'user' => UserResource::make($user)
            ]
        ]);
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse($request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
