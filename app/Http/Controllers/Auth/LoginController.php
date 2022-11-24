<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

    protected function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['كلمة المرور أو البريد الالكتروني غير صحيحين.'],
            ]);
        }

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'access_token' => explode('|', $user->createToken('personal-access-token')->plainTextToken)[1],
                'user' => UserResource::make($user)
            ]
        ], 200);


    }

    protected function sendLoginResponse(Request $request)
    {
        $user = $this->guard($request->get('type'))->user();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $user);
    }

    protected function guard($guard)
    {
        return Auth::guard($guard);
    }

    protected function authenticated(Request $request, $user)
    {
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'access_token' => explode('|', $user->createToken('personal-access-token')->plainTextToken)[1],
                'user' => UserResource::make($user)
            ]
        ], 200);
    }


    protected function sendFailedLoginResponse()
    {
        return response()->json([
            'message' => __('auth.failed'),
        ], 400);
    }

//    public function user(Request $request)
//    {
//        return response()->json([
//            'user' => UserResource::make($request->user())
//        ]);
//    }
}
