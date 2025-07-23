<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Providers\AppServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    use AsAction;

    /**
     * Define the validation rules for the incoming request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Handle the incoming request (login logic).
     */
    public function handle(Request $request)
    {
        // Validasi otomatis oleh AsAction berkat method rules()
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(AppServiceProvider::HOME);
    }
}
