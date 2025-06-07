<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SensitiveOperationsLogger;
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
        $user = $request->user();

        // Get token info before deletion for logging
        $token = $user->currentAccessToken();

        // Log the logout attempt
        SensitiveOperationsLogger::attempt('logout', 'user', $user->id, [
            'email' => $user->email,
            'token_id' => $token->id
        ]);

        try {
            // Delete the current access token
            $token->delete();

            // Log successful logout
            SensitiveOperationsLogger::success('logout', 'user', $user->id, [
                'email' => $user->email,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'message' => 'تم تسجيل الخروج بنجاح',
            ]);
        } catch (\Exception $e) {
            // Log failed logout
            SensitiveOperationsLogger::failure('logout', 'user', $user->id, [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    protected function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Log the login attempt
        SensitiveOperationsLogger::attempt('login', 'auth', null, [
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Check if the user has too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            // Log account lockout
            SensitiveOperationsLogger::failure('login', 'auth', null, [
                'email' => $request->email,
                'reason' => 'Account locked due to too many failed attempts',
                'ip_address' => $request->ip()
            ]);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->incrementLoginAttempts($request);

            // Log failed login
            SensitiveOperationsLogger::failure('login', 'auth', $user ? $user->id : null, [
                'email' => $request->email,
                'reason' => $user ? 'Invalid password' : 'User not found',
                'ip_address' => $request->ip(),
                'attempts' => $this->limiter()->attempts($this->throttleKey($request))
            ]);

            return $this->sendFailedLoginResponse($request);
        }

        // Reset login attempts on successful login
        $this->clearLoginAttempts($request);

        // Load roles with their permissions
        $user->load(['roles.permissions']);

        // Log successful login
        SensitiveOperationsLogger::success('login', 'auth', $user->id, [
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'roles' => $user->roles->pluck('slug')->toArray()
        ]);

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
     * @param  \Illuminate\Http\Request $request
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
        $token = $user->createToken('personal-access-token');
        $plainTextToken = explode('|', $token->plainTextToken)[1];

        // Log token creation
        SensitiveOperationsLogger::success('token_created', 'auth', $user->id, [
            'token_id' => $token->accessToken->id,
            'email' => $user->email,
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'access_token' => $plainTextToken,
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
