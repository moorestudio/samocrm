<?php

namespace App;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Hash;

// class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name','middle_name','email', 'password', 'city', 'country', 'phone', 'job', 'work_type', 'company', 'contacts','confirmation_token'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function partners()
    {
        return $this->hasMany(User::class,'franchise_id');
    }

    public function franchise()
    {
        return $this->belongsTo(User::class);
    }

    public function clients()
    {
        return $this->hasMany(User::class,'franchise_id');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function Histories()
    {
        return $this->hasMany(History::class);
    }

    public function ticket_count()
    {

        $count = Ticket::all()->where('user_id',$this->id)->whereIn('type',['buy','done']);
        $count = $count ? count($count) : 0;
        return $count;
    }
    public function fullName()
    {
        return ($this->last_name.' '. $this->name.' '. $this->middle_name);
    }
    ////////////////////////////////////////////////////////////////////////
    protected $dates = [
    'created_at', 'updated_at', 'confirmed_at'
    ];
    /**

 * Generate a random hexadecimal token by hashing the current time in microseconds as float
 *
 * @return string Random 32-characters long hexadecimal token
 */
    public static function generateToken() {
        return md5(microtime(true));
    }

    /**
     * Check if the confirmation_token field in the database is NULLed or not
     *
     * @return bool Whether the user has confirmed his e-mail address or not
     */
    public function hasConfirmed() {
        return $this->confirmation_token === null ? true : false;
    }

    /**
     * Try to confirm the user's e-mail address using the provided token
     *
     * @param $token string The user-provided token
     * @return bool Whether the confirmation succeed or not.
     */
    public function confirm($token) {
        // If the user has already confirmed we can't confirm him again.
        if ($this->hasConfirmed()) return false;

        if ($token === $this->confirmation_token) {
            // User has confirmed his e-mail address.
            $this->confirmation_token = null;
            $this->confirmed_at = \Carbon\Carbon::now();
            $this->save();

            return true;
        }

        // Token was incorrect.
        return false;
    }

    /**
     * Try to unconfirm the user's e-mail address
     *
     * @return bool Whether the unconfirmation succeed or not.
     */
    public function unconfirm() {
        // If the user is not even confirmed we can't unconfirm him.
        if (!$this->hasConfirmed()) return false;

        // Reset token with a newly generated one and save the model.
        $this->confirmation_token = User::generateToken();
        $this->confirmed_at = null;
        $this->save();

        return true;
    }
    public function promoname(){
      $promo = 'нет промокода';
      $ticket = Ticket::where('user_id',$this->id)->orderBy('id','desc')->first();
      if($ticket){
        if($ticket->promo_name){
          $promo = $ticket->promo_name;
        }
      }
      return $promo;
    }
    public function promodiscount(){
      $promo = '';
      $ticket = Ticket::where('user_id',$this->id)->orderBy('id','desc')->first();
      if($ticket){
        if($ticket->promo_discount){
          $promo = $ticket->promo_discount;
        }
      }
      return $promo;
    }
    public function getSumFromFinancial($financials){
      $num = 0;
      $arr =[];
      foreach($financials as $financial){
        foreach($financial as $info){
          if($info['partner_id']==$this->id){
            $num += $info['partners_franchise_sum'];
            $temp_cur = [
              "curr"=>$info['ticket_currency_id'],
              "value"=>$info['partners_franchise_sum'],
            ];
            array_push($arr, $temp_cur);
        
          }
        }
      }
      /////////////////////////
    $temp = [];
    foreach($arr as $value) {
    //check if color exists in the temp array
    if(!array_key_exists($value['curr'], $temp)) {
        //if it does not exist, create it with a value of 0
        $temp[$value['curr']] = 0;
    }
    //Add up the values from each color
    $temp[$value['curr']] += $value['value'];
    }
///////////////////////////////
      return $temp;
    }
/////////////////////////////////////////////////////////////////////////
      public function getFranchPercent($financials){

      $arr =[];
      foreach($financials as $financial){
        foreach($financial as $info){
          if($info['user_id']==$this->id){
            
            $temp_cur = [
              "curr"=>$info['ticket_currency_id'],
              "value"=>$info['sum'],
            ];
            array_push($arr, $temp_cur);
        
          }
        }
      }
      /////////////////////////
    $temp = [];
    foreach($arr as $value) {
    //check if color exists in the temp array
    if(!array_key_exists($value['curr'], $temp)) {
        //if it does not exist, create it with a value of 0
        $temp[$value['curr']] = 0;
    }
    //Add up the values from each color
    $temp[$value['curr']] += $value['value'];
    }
///////////////////////////////
      return $temp;
    }
    public function dateFormat($date){
      $date = Carbon::make($this->created_at)->format($date);
      return $date;
  }

  public function sendPasswordResetNotification($token)
  {
     $this->notify(new ResetPassword($token));
  }

}
