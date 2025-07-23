<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Verified;

class VerifyUserEmail
{
    use AsAction;

    /**
     * Handle the incoming request (email verification logic).
     */
    public function handle(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(AppServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(AppServiceProvider::HOME.'?verified=1');
    }
}
