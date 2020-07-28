<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Cookie;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    protected function redirectTo()
    {
        return '/email_sent';
        // return url()->previous();
    }
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }




    // protected function redirectTo()
    // {
    //   if (Auth::user()->role==0) {
    //     return '/dashboard';
    //   } else {
    //     return '/home';
    //   }
    // }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        // dd($this->redirectPath());
        $orig_url_arr  = explode("/",\URL::previous());
        if(isset($orig_url_arr[3]) && isset($orig_url_arr[4])){
            if(($orig_url_arr[3]=="buy" && $orig_url_arr[4]) || ($orig_url_arr[3]=="info" && $orig_url_arr[4])){
                $event_id = $orig_url_arr[4];
                Cookie::queue('orig_event_id', $event_id, 60);
                Cookie::queue('orig_event_type', $orig_url_arr[3], 60);
            }
        };
    }

    ////////////////////////////////////////////////////////////////////////
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if($data['work_type'] !== '6'){
            $data['other_work_type'] = 'empty';
        }
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8'],
            'contacts' => ['required', 'string', 'min:8', 'unique:users'],// судя по всему это к contacts
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'job' => ['required', 'string'],
            'company' => ['required', 'string'],
            'work_type' => ['required', 'string'],
            'other_work_type' => ['required', 'string'],
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $work_type = $data['work_type'];
        if ($data['work_type'] == '6'){
            $work_type = $data['other_work_type'];
        };

        $random_pass = str_random(8);
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'email' => $data['email'],
            'password' => Hash::make($random_pass),
            'contacts' => $data['contacts'],
            'city' => $data['city'],
            'job' => $data['job'],
            'country' => $data['country'],
            'work_type' => $work_type,
            'company' => $data['company'],
            'confirmation_token' => User::generateToken(),
        ]);
        event(new \App\Events\UserReferred(request()->cookie('ref'), $user));
        event(new \App\Events\UserRegistered($user,$random_pass));
        return $user;

    }


}
