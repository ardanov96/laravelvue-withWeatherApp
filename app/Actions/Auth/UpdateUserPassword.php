<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdateUserPassword
{
    use AsAction;

    /**
     * Define the validation rules for the incoming request.
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Rules\Password::defaults(), 'confirmed'],
        ];
    }

    /**
     * Handle the incoming request (update user password logic).
     */
    public function handle(Request $request)
    {
        // Validasi otomatis oleh AsAction

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }
}
