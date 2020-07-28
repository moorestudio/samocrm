<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Cookie;
use App\ReferralLink;
use App\User;
use App\UsersActivity;
use Carbon\Carbon;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo;

    public function redirectTo()
    {
        if(Auth::check()){
          $activity = new UsersActivity;
          $activity->date = Carbon::now()->format('Y-m-d');
          $activity->time = Carbon::now()->format('H:i:s');
          $activity->user_id = auth()->user()->id;
          $activity->type = 'visit';
          $activity->save();
          if(auth()->user()->status =='blocked'){
              Auth::logout();
              session()->invalidate();
              session(['userStatus' => 'blocked']);
              return '/login';
          }
        }
        switch(Auth::user()->role_id){
            case 2:
                if(Cookie::get('orig_event_id') !== null){
                    $event_orig_id=Cookie::get('orig_event_id');
                    $event_orig_type=Cookie::get('orig_event_type');
                    if($event_orig_type == 'buy'){
                      $this->redirectTo = route('buy',['id' => $event_orig_id]);
                    }
                    elseif($event_orig_type == 'info'){
                      $this->redirectTo = route('event.view',['id' => $event_orig_id]);
                    }
                    
                    Cookie::queue(Cookie::forget('orig_event_id'));
                    Cookie::queue(Cookie::forget('orig_event_type'));
                    return $this->redirectTo;
                    break;
                }
                else{
                    $this->redirectTo = route('home');
                    return $this->redirectTo;
                    break;
                }
            case 3:
                $this->redirectTo = route('admin');
                return $this->redirectTo;
                break;
            case 4 || 5 || 8:
                $this->redirectTo = route('event_list');
                return $this->redirectTo;
                break;
            case 7:
                $this->redirectTo = route('scanner_list');
                return $this->redirectTo;
                break;
            default:
                $this->redirectTo = route('main');
                return $this->redirectTo;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
