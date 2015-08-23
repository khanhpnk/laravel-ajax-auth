<?php

namespace Rukan\AjaxAuth;

use App\Http\Controllers\Controller;
use Rukan\AjaxAuth\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
