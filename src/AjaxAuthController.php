<?php
namespace Rukan\AjaxAuth;

use App\Http\Controllers\Controller;
use Rukan\AjaxAuth\Auth\AuthenticatesAndRegistersUsers;


class AjaxAuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
}