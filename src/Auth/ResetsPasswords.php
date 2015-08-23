<?php

namespace Rukan\AjaxAuth\Auth;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Rukan\AjaxAuth\Requests\PasswordRequest;
use Rukan\AjaxAuth\Requests\ResetRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\Message;

trait ResetsPasswords
{
    /**
     *
     * @var string
     */
    protected $redirectAfterReset = 'home';

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return view('auth.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Rukan\AjaxAuth\Requests\PasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(PasswordRequest $request)
    {
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(Lang::get('passwords.subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $request->ajax() ? new JsonResponse([
                    'message' => Lang::get($response)
                ]) : redirect()->back()->with('status', Lang::get($response));
            case Password::INVALID_USER:
                return $request->ajax() ? new JsonResponse([
                    'message' => Lang::get($response)
                ], 404) : redirect()->back()->withErrors(['email' => Lang::get($response)]);
        }

    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('auth.reset')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param \Rukan\AjaxAuth\Requests\ResetRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(ResetRequest $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectAfterReset);
            default:
                return redirect()->back()
                            ->withInput($request->only('email'))
                            ->withErrors(['email' => Lang::get($response)]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();

        Auth::login($user);
    }
}
