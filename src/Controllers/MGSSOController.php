<?php namespace InspireSoftware\MGSSO\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use InspireSoftware\MGSSO\MGSSOBroker;

class MGSSOController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, AuthenticatesUsers;

    public function login(Request $request, MGSSOBroker $mgBroker)
    {
        $this->validateLogin($request);
        $loginResult = $mgBroker->loginUser($request->get('email'),$request->get('password'));

        if($loginResult){
            
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }
            
            return $mgBroker->loginCurrentUser();
            // return $this->sendLoginResponse($request);
            
        }
        
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
