<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $this->validator($request->all())->validate();

        // Create and register the user
        $user = $this->create($request->all());
        event(new Registered($user));
        
        // Send email verification notification
        $user->sendEmailVerificationNotification();

        // Create access token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Return response with verification status
        return response()->json([
            'message' => 'Registration successful. Please check your email to verify your account.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'email_verified' => false,
            'verification_required' => true,
        ], 201);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).+$/',
            ],
        ], [
            'password.regex' => 'The password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'admin' => $data['type'] === 'api',
        ]);
    }
}
