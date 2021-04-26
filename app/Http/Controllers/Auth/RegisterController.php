<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validation = [
            'username' => ['required', 'string', 'max:255', 'unique:users', 'regex:/^\S*$/u'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
        if ($data['type'] === 'student') {
            $validation = [
                'username' => ['required', 'max:255', 'unique:students', 'regex:/^\S*$/u'],
                'email' => ['required', 'max:255', 'unique:students', 'email'],
                'first_name' => ['required', 'max:255'],
                'last_name' => ['required', 'max:255'],
                'reg_year' => ['required', 'numeric', 'max:' . date('Y')],
                'gender' => ['required', 'in:male,female'],
                'address' => ['required', 'max:255'],
                'password' => ['required', 'max:255', 'min:8'],
                'mobile_number' => ['required', 'max:50', 'string'],
            ];
        }
        return Validator::make($data, $validation);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = null;
        if ($data['type'] === 'api') {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'admin' => 0,
                'password' => Hash::make($data['password']),
            ]);
        } elseif ($data['type'] === 'student') {
            $user = Student::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'reg_year' => $data['reg_year'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'password' => Hash::make($data['password']),
                'mobile_number' => $data['mobile_number'],
            ]);
        }
        return $user;
    }

    protected function registered(Request $request, $user)
    {
        return response()->json([
            'message' => 'Registered Successfully',
            'data' => [
                'access_token' => explode('|', $user->createToken('personal-access-token')->plainTextToken)[1],
                'user' => get_class($user) === Student::class ? StudentResource::make($user) : UserResource::make($user)
            ]
        ], 201);
    }
}
