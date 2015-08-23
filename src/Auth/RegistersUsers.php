<?php

namespace Rukan\AjaxAuth\Auth;

use Rukan\AjaxAuth\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\User;

trait RegistersUsers
{
    /**
     * Register success redirect
     *
     * @var string
     */
    protected $redirectAfterRegister = 'home';

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Rukan\AjaxAuth\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(UserRequest $request)
    {
        Auth::login($this->create($request->all()));

        return $request->ajax() ? new JsonResponse([
            'redirect' => $this->redirectAfterRegister
        ]) : redirect($this->redirectAfterRegister);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
