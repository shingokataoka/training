<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated owner's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->owner()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::OWNER_HOME.'?verified=1');
        }

        if ($request->owner()->markEmailAsVerified()) {
            event(new Verified($request->owner()));
        }

        return redirect()->intended(RouteServiceProvider::OWNER_HOME.'?verified=1');
    }
}
