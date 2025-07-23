<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\ValidationException;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Auth;

class ConfirmUserPassword
{
    use AsAction;

    /**
     * Define the validation rules for the incoming request.
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Handle the incoming request (password confirmation logic).
     */
    public function handle(Request $request)
    {
        // Validasi otomatis oleh AsAction
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(AppServiceProvider::HOME);
    }
}
