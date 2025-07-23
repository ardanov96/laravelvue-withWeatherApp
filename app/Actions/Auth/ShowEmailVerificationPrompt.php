<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Providers\AppServiceProvider;

class ShowEmailVerificationPrompt
{
    use AsAction;

    /**s
     * Handle the incoming request (show email verification prompt logic).
     */
    public function handle(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(AppServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}
