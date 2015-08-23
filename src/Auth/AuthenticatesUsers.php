<?php

namespace Rukan\AjaxAuth\Auth;

use Rukan\AjaxAuth\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\JsonResponse;

trait AuthenticatesUsers
{
    /**
     *
     * @var string
     */
    protected $redirectAfterLogin = '/';

    /**
     *
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';

    /**
     * Login fail redirect to the path login form
     *
     * @var string
     */
    protected $loginPath = 'auth/login';

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Rukan\AjaxAuth\Requests\LoginUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(LoginUserRequest $request)
    {
        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $request->ajax() ? new JsonResponse([
                'redirect' => $this->redirectAfterLogin
            ]) : redirect()->intended($this->redirectAfterLogin);
        }

        $error = ['message' => Lang::get('auth.failed')];
        return $request->ajax() ? new JsonResponse($error, 404) : redirect($this->loginPath)
            ->withInput($request->only('email', 'remember'))
            ->withErrors($error);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect($this->redirectAfterLogout);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Rukan\AjaxAuth\Requests\LoginUserRequest $request
     * @return array
     */
    protected function getCredentials(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (config('ajax-auth.active')) {
            $credentials['active'] = 1;
        }

        return $credentials;
    }
}
