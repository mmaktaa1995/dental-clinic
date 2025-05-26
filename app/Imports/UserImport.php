<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserImport extends BaseImport
{
    /**
     * Process a single row
     *
     * @param Collection $row
     * @return void
     */
    protected function processRow(Collection $row)
    {
        $user = new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? Str::random(10)),
            'email_verified_at' => now(),
        ]);

        $user->save();

        return $user;
    }

    /**
     * Get validation rules for the import
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'The user name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.min' => 'The password must be at least 8 characters.',
        ];
    }

    /**
     * Get custom validation attributes
     *
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [
            'name' => 'user name',
            'email' => 'email address',
            'password' => 'password',
        ];
    }
}
