<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\EventFranchFind;
use App\Financial;
use App\Hall;
use App\LostClients;
use App\Ticket;
use Auth;
use App\Role;
use App\User;
use App\PendingUser;
use App\partner_referral_relationship;
use Hash;
use App\ReferralLink;
use App\ReferralRelationship;
use App\Mail\FranchInvitation;
use App\Mail\ChangeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\partner_referral_link;
use Carbon\Carbon;
use Cookie;
use DB;
use YandexCheckout\Client;
use Illuminate\Support\Str;
class FranchiseController extends Controller
{
    public function index()
    {
        $franchise_all = User::all()->where('role_id', 4);
        $partners_all = User::all()->where('role_id', 5);
        $sales_all = User::all()->where('role_id', 8);

        // dd($franchise_all);
        $data = [
            "franchise_all"=>$franchise_all,
            "partners_all"=>$partners_all,
            "sales_all"=>$sales_all,
        ];
        return view('admin.franchise.index',['data' => $data]);
    }

    public function show(Request $request, $id){
    	$user = User::find($id);
        $user_links = ReferralLink::all()->where("user_id",$id);

        // dd($user_links->count());
        if($user_links->count()>0){
            $user_links_ids = ReferralLink::all()->where("user_id",$id)->pluck('id');
            $user_links_event_ids = ReferralLink::all()->where("user_id",$id)->pluck('referral_event_id');
            $users_reg_with_ur_link=[];
            foreach ($user_links_ids as  $id) {
                array_push($users_reg_with_ur_link,ReferralRelationship::all()->where("referral_link_id",$id)->pluck('user_id')->toArray());
            };
            $result_all_referred_ids = call_user_func_array('array_merge', $users_reg_with_ur_link);
            $users_reg_with_ur_link_obj = User::whereIn('id',$result_all_referred_ids)->get();

        }
        else{
            $users_reg_with_ur_link_obj=null;
        }
        $profile_data=[
            'user' => $user,
            'user_links'=>$user_links,
            'referred_users'=>$users_reg_with_ur_link_obj,
        ];
    	return view('admin.franchise.show',['user' => $user,'user_links' => $user_links,'referred_users' => $users_reg_with_ur_link_obj,]);
    }

    public function create(){
    	$franchise_all = User::all()->whereIn('role_id',[4,6]);
        $users_data = [
            "franchise_all"=>$franchise_all,
        ];
        return view('admin.franchise.create',['users_data' => $users_data]);
    }

    public function store(Request $request, $id=null){

    	/////////////Валидация/////////////////
        $request->validate([
            'contract' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
            // 'contract' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:5120',
            'user_type' => 'required',
        ]);

        $imageName_contract = request()->inn . '_' . '.' . rand(1, 1400) . '.png';
        // request()->contract->move(public_path('images/contracts'), $imageName_contract);
        $user_type = $request->user_type;
        $name = $request->name;
        $last_name = $request->last_name;
        $middle_name = $request->middle_name;
        $city = $request->city;
        $country = $request->country;
        $contacts = $request->contacts;
        $inn = $request->inn;
        $email = $request->email;
        $contract_date = $request->date;
        $contract_date_end = $request->date_end;
        $contract = $request->contract;
        $amo_id = $request->amo_id;
        // $password = $request->password;
        $comments = $request->comments;
        $percent_from_sales = $request->franch_percent;// % от продаж билетов клиентам
        $percent_from_partner = $request->percent_from_partner;// % от продаж партнера --- может быть только у франчайзи
        $new_email = True;
        $new_user= True;
        if (User::find($id)) {
            $new_user= False;
            $new_email = False;
            $user = User::find($id);
            if ($user->email != $email) {
                $new_email = True;
                //если email изменился
                $request->validate([
                    'email' => 'required|email|unique:users',
                ]);
                $user->email = $email;
            };
            if ($user->INN != $inn) {
                $request->validate([
                    'inn' => 'required|digits_between:10,14|unique:users',
                ]);
                $user->INN = $inn;
            };
            if ($user->contacts != $contacts) {
                $request->validate([
                    'contacts' => 'required|unique:users|min:8',
                ]);
                $user->contacts = $contacts;
            };
            
            $password = $request->password;
            $random_pass = $password;
            if ($password != "" && $password!=null) {
                $user->password = Hash::make($password);
            }
        } else {
            $user = new User();
            $request->validate([
                'email' => 'required|email|unique:users',
                'inn' => 'required|digits_between:10,14|unique:users',
                'contacts' => 'required|unique:users|min:8',
            ]);
            $user->email = $email;
            $random_pass = str_random(8);
            $user->password = Hash::make($random_pass);
        }
        $user->name = $name;
        $user->last_name = $last_name;
        $user->city = $city;
        $user->country = $country;
        $user->contacts = $contacts;
        $user->INN = $inn;
        $user->contract_date = $contract_date;
        $user->contract_date_end = $contract_date_end;
        $user->amo_id = $amo_id;

        $public_path = public_path();

        if ($request->hasFile('contract')) {
            $imageName_contract = request()->inn . '_' . '.' . request()->contract->getClientOriginalExtension();

            $path = $request->file('contract')->storeAs('public/images/contracts', $imageName_contract);
            // request()->contract->move(public_path('images/contracts'), $imageName_contract);
            // $contract_img_path = $public_path."\images\contracts\\".$imageName_contract;

            $user->contract = $imageName_contract;
        }

        $user->comments = $comments;
        $user->middle_name = $middle_name; // может убрать?
        $user->percent = $percent_from_sales;
        if ($user_type == 'franchise') {
            $user->percent_from_partner = $percent_from_partner;
            if($new_user){
                $user->role_id = 4;//// 4 это event manager / franchise
            }

        } elseif ($user_type == 'sales') {
            $user->role_id = 8;//// 8 это sales
        } else {

            if ($request->franchise_selection_choice == "SAMO") {
                $user->franchise_id = null; //// партнер прикреплен за САМО
                $user->role_id = 5;
            } else {
                $validatedData = $request->validate([
                    'franchise' => 'required',
                ]);
                $user->role_id = 5;//// 5 это partner с франч
                $user->franchise_id = $request->franchise;
            }

            // $user->franchise_name = User::find($request->franchise)->name;
        }
        // $user->avatar = 'default.png';
        $user->save();
        $log_url = url('/login');
        if ($new_email) {
            Mail::to($user->email)->send(new FranchInvitation($user, $random_pass, $log_url));
        }
        return redirect()->route('user_list');
    }


    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $franchise_all = DB::table('users')->whereIn('role_id', [4, 6])->where('country', 'LIKE', '%' . $request->search . "%")->get();
            if ($franchise_all) {
                foreach ($franchise_all as $franchise) {
                    $output .= '<div class="row" onclick="get_franch(this)" franch_name="' . $franchise->last_name . ' ' . $franchise->name . ' ' . $franchise->middle_name . '" class="franch_row_class" franch_id="' . $franchise->id . '">' .
                        '<div class="col-4">' . $franchise->last_name . ' ' . $franchise->name . ' ' . $franchise->middle_name . '</div>' .
                        '<div class="col-2">' . $franchise->country . '</div>' .
                        '<div class="col-2">' . $franchise->city . '</div>' .
                        '<div class="col-2">' . $franchise->contract_date . '</div>' .
                        '<div class="col-2">' . $franchise->contract_date_end . '</div>' .
                        '</div>';
                }
                return Response($output);
            }
        }
    }


    public function search_admin(Request $request)
    {
        $search = $request->search;

        if ($request->ajax()) {
            $output = "";

            if($search !=null){
                $franchise_all=DB::table('users')
                    ->whereIn('role_id', [4,5,6,8])
                    ->where('status',null)                
                    ->where(function($query) use ($search){
                        $query->where('users.name', 'LIKE', '%'.$search.'%');
                        $query->orWhere('users.last_name', 'LIKE', '%'.$search.'%');
                        $query->orWhere('users.middle_name', 'LIKE', '%'.$search.'%');
                        $query->orWhere('users.email', 'LIKE', '%'.$search.'%');
                        $query->orWhere('users.contacts', 'LIKE', '%'.$search.'%');
                    })->get();
                }
            else{
                $franchise_all=DB::table('users')
                    ->where('status',null)
                    ->whereIn('role_id', [4,5,6,8])->get();
            }   
            if ($franchise_all) {
                foreach ($franchise_all as $franchise) {
                    $output .= '<div class="row create_user_inputs my-2 p-2" onclick="get_franch(this)" franch_name="' . $franchise->last_name . ' ' . $franchise->name . ' ' . $franchise->middle_name . '" class="franch_row_class" franch_id="' . $franchise->id . '">' .
                        '<div class="col-3">' . $franchise->last_name . ' ' . $franchise->name . ' ' . $franchise->middle_name . '</div>' .
                        '<div class="col-3">' . Role::find($franchise->role_id)->display_name . '</div>' .
                        '<div class="col-2">' . $franchise->country . '</div>' .
                        '<div class="col-4">' . $franchise->email . '</div>' .
                        '</div>';
                }
                return Response($output);
            }



        }
    }




    public function update(Request $request, $id)
    {
        $franchise_all = User::all()->where('role_id', 4);
        $user = User::find($id);
        $user_type = null;
        $partner_belongs_to = null;
        $partners_franch_id = null;
        if ($user->role_id == 4 || $user->role_id == 6) {
            $user_type = "Franchise";
        } elseif ($user->role_id == 8) {
            $user_type = "Sales";
        } elseif ($user->role_id == 5 && $user->franchise_id == null) {
            $user_type = "Partner";
            $partner_belongs_to = "Samo Office";
        } elseif ($user->role_id == 5 && $user->franchise_id != null) {
            $user_type = "Partner";
            $partner_belongs_to = User::find($user->franchise_id)->fullName();
            $partners_franch_id = $user->franchise_id;
        }
        $users_data = [
            "franchise_all" => $franchise_all,
            "user" => $user,
            "user_type" => $user_type,
            "partner_belongs_to" => $partner_belongs_to,
            "partners_franch_id" => $partners_franch_id
        ];
        // dd($users_data);
        return view('admin.franchise.create', ['users_data' => $users_data]);
    }

    public function partners_list(Request $request)
    {
        // $user = User::find($id);
        // $partners = User::all()->where('franchise_id',$id)->where('role_id', 5);
        $all_financial = Financial::all()->pluck('franch_perc_from_part')->filter(function ($value, $key) {
            return $value != null;
        });
        // dd($all_financial);
        // $franch_perc_from_part = collect($financial->franch_perc_from_part);
        // dd($franch_perc_from_part);
        return view('admin.franchise.show_partners')->with('all_financial', $all_financial);
    }

    public function profile(Request $request, $id)
    {
        $user = User::find($id);
        $user_links = ReferralLink::all()->where("user_id", $id);

        // dd($user_links->count());
        if ($user_links->count() > 0) {
            $user_links_ids = ReferralLink::all()->where("user_id", $id)->pluck('id');
            $user_links_event_ids = ReferralLink::all()->where("user_id", $id)->pluck('referral_event_id');
            $users_reg_with_ur_link = [];
            foreach ($user_links_ids as $id) {
                array_push($users_reg_with_ur_link, ReferralRelationship::all()->where("referral_link_id", $id)->pluck('user_id')->toArray());
            };
            $result_all_referred_ids = call_user_func_array('array_merge', $users_reg_with_ur_link);
            $users_reg_with_ur_link_obj = User::whereIn('id', $result_all_referred_ids)->get();

        } else {
            $users_reg_with_ur_link_obj = null;
        }
        $part_ref_link = partner_referral_link::where('user_id', Auth::user()->id)->first();
        $profile_data = [
            'user' => $user,
            'user_links' => $user_links,
            'referred_users' => $users_reg_with_ur_link_obj,
        ];
        return view('admin.franchise.profile', ['profile_data' => $profile_data, 'part_ref_link' => $part_ref_link]);
    }

    public function profile_edit(Request $request, $id)
    {
        $user = User::find($id);
        return view('admin.franchise.profile_edit', ['user' => $user]);
    }

    public function profile_image_update(Request $request, $id)
    {

        $user = User::find($id);
        if ($request->avatar) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $avatarName = $user->id . '_avatar' . '.' . request()->avatar->getClientOriginalExtension();

            $request->avatar->storeAs('public/avatars', $avatarName);
            $user->avatar = $avatarName;
            // dd($user->avatar);
        }
        $user->save();
        // dd($user);
        return Redirect::back();
    }


    public function profile_update(Request $request, $id)
    {
        $work_type = $request['work_type'];
        if ($request['work_type'] == '6') {
            $work_type = $request['other_work_type'];
        };
        $user = User::find($id);
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->city = $request->city;
        $user->country = $request->country;

        $user->job = $request->job;
        $user->work_type = $work_type;

        $password = $request->password;
        // $user->contacts = $request->contacts;
        $new_email = False;
        if ($password != "") {
            $user->password = Hash::make($password);
        }
        ////
        if ($user->email != $request->email) {
            $new_email = True;
            //если email изменился
            $request->validate([
                'email' => 'required|email|unique:users',
            ]);
            $user->email = $request->email;
        };
        if ($user->contacts != $request->contacts) {
            $request->validate([
                'contacts' => 'required|unique:users|min:8',
            ]);
            $user->contacts = $request->contacts;
        };


        $user->save();
        if ($new_email) {
            $user->unconfirm();
            $user->save();
            Mail::to($user->email)->send(new ChangeEmail($user));
        }
        if (\Illuminate\Support\Facades\Auth::user()->id != $user->id) {
            return redirect()->route('user_profile', ['user_id' => $user->id]);
        }
        return \Redirect('profile');
    }

    public function clients()
    {
        $franch = \Illuminate\Support\Facades\Auth::user();

        $clients = $franch->clients->where('role_id', 2)->sortByDesc('last_name');
        $lost = LostClients::select('client_id')->where('franch_id', $franch->id)->get();

        $franch_event_connect = EventFranchFind::where('franchise_id', $franch->id)->get();

        $event_financial = collect();
        foreach ($franch_event_connect as $item) {
            $event_financial->push(Financial::where('event_id', $item->event_id)->first());
        }


        $lost_clients = collect();
        foreach ($lost as $item) {
            $lost_clients->push(User::find($item->client_id));
        }

        $lost_clients = $lost_clients->unique();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $all_financial = Financial::all()->pluck('franch_perc_from_part')->filter(function ($value, $key) {
            return $value != null;
        });

        $partners_list = $franch->clients->where('role_id', 5)->sortByDesc('last_name');

        $total_from_partners = [];
        foreach ($partners_list as $partner) {
            $sum__ = $partner->getSumFromFinancial($all_financial);
            if ($sum__) {
                array_push($total_from_partners, $sum__);
            }

            // $total_from_partners+=$partner->getSumFromFinancial($all_financial);
        }

        $temp = [];
        foreach ($total_from_partners as $sub_arr) {
            foreach ($sub_arr as $key_ => $value_) {
                if (array_key_exists($key_, $temp)) {
                    $temp[$key_] += $value_;
                } else {
                    $temp[$key_] = $value_;
                }
            }

        }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $all_franch_percent = Financial::all()->pluck('franch_percent')->filter(function ($value, $key) {
            return $value != null;
        });
        $franch_percent_values = $franch->getFranchPercent($all_franch_percent);

        return view('admin.franchise.clients.clients_list', ['franch' => $franch, 'clients' => $clients, 'lost_clients' => $lost_clients, 'financials' => $event_financial, 'bonus_from_part' => $temp, 'franch_percent_values' => $franch_percent_values]);
    }

    public function part_clients_for_franch(Request $request, User $franch)
    {

        // $franch = \Illuminate\Support\Facades\Auth::user();
        $clients = $franch->clients->where('role_id', 2)->sortByDesc('last_name');

        $lost = LostClients::select('client_id')->where('franch_id', $franch->id)->get();

        $franch_event_connect = EventFranchFind::where('franchise_id', $franch->id)->get();

        $event_financial = collect();
        foreach ($franch_event_connect as $item) {
            $event_financial->push(Financial::where('event_id', $item->event_id)->first());
        }

        $lost_clients = collect();
        foreach ($lost as $item) {
            $lost_clients->push(User::find($item->client_id));
        }

        $lost_clients = $lost_clients->unique();

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $all_franch_percent = Financial::all()->pluck('franch_percent')->filter(function ($value, $key) {
            return $value != null;
        });
        $franch_percent_values = $franch->getFranchPercent($all_franch_percent);


        return view('admin.franchise.clients.clients_list', ['franch' => $franch, 'clients' => $clients, 'lost_clients' => $lost_clients, 'financials' => $event_financial, 'franch_percent_values' => $franch_percent_values]);
    }

    public function EventAdminReport()
    {
        if (Auth::user()->role_id == 3) {
            $events = Event::all();
        } else if (Auth::user()->role_id == 6) {
            $user_id = Auth::user()->id;
            $events = Event::where('user_id', $user_id)->get();
        }


        return view('admin.reports.event_admin.reports', ['events' => $events]);
    }


    public function EventAdminReportSingleEvent(Event $event)
    {
        // dd($event->tickets);
        $financial = Financial::where('event_id', $event->id)->first();
        $eventAdmin = $event->user_id;
        $eventSales = 0;
        $samoSales = 0;
        $partnerSales = 0;
        $franchiseSales = 0;


        $franch_per = $financial->franch_percent;
        $final = array();
        if($franch_per){
            foreach ($franch_per as $result) {
                if(!isset($final[$result['user_id']])) {
                    $final[$result['user_id']] = [$result['sum'],$result['franch_percent'],$result['partners_franchise_sum'],$result['part_franch_percent'],$result['franch_status'],$result['franch_name']];
                } else {
                    $final[$result['user_id']][0] += $result['sum'];
                    $final[$result['user_id']][2] += $result['partners_franchise_sum'];
                }
            }
        }
        // dd($final);

        foreach ($event->tickets as $ticket) {
            // dd($ticket->user);
            if ($ticket->type != 'return' && $ticket->type != 'reserve') {
                // dd($ticket);
                if ($ticket->seller_id == $ticket->event_admin_id) {
                    $eventSales = $eventSales + $ticket->ticket_price;
                    continue;
                }
                if ($ticket->seller_id == null) {
                    $samoSales = $samoSales + $ticket->ticket_price;
                    continue;
                }
                if (isset($ticket->seller_id) && $ticket->seller_role_id == 5) {
                    $partnerSales = $partnerSales + $ticket->ticket_price;
                }
                if (isset($ticket->seller_id) && $ticket->seller_role_id == 8) {
                    $partnerSales = $partnerSales + $ticket->ticket_price;
                }
                if (isset($ticket->seller_id) && $ticket->seller_role_id == 4 && $ticket->seller_id != $ticket->event_admin_id || isset($ticket->seller_id) && $ticket->seller_role_id == 6 && $ticket->seller_id != $ticket->event_admin_id) {
                    $franchiseSales = $franchiseSales + $ticket->ticket_price;
                }
            }
        }
        $row = Hall::where('event_id', $event->id)->count();
        $column = Hall::where('event_id', $event->id)->first();
        $halls = Hall::where('event_id', $event->id)->get();
        $tickets = Ticket::where('event_id', $event->id)->get();
        if ($column) {
            $collect = collect($column->column);
            $count = $collect->count();
        } else {
            $count = null;
        }
        $total_raw_income = 0;
        foreach (collect($financial->raw_income) as $raw) {
            $total_raw_income += $raw['value'];
        }
////////////////////////////////////
        $reff_links = ReferralLink::where('referral_event_id', $event->id)->get();
////////////////////////////////////

        return view('admin.reports.event_admin.report_single_event', ['event' => $event, 'financial' => $financial, 'halls' => $halls, 'row' => $row, 'column' => $count, 'tickets' => $tickets, 'EventSales' => $eventSales, 'SamoSales' => $samoSales, 'PartnerSales' => $partnerSales, 'FranchiseSales' => $franchiseSales, 'total_raw_income' => $total_raw_income, 'reff_links' => $reff_links,'subtotals' => $final]);
    }
    function getFinStats($stat_name,$event_ids){
            $all_financial_stats = Financial::all()->whereIn('event_id',$event_ids)->pluck($stat_name, 'event_id')->filter(function ($value, $key) {
                return $value != null && $value != 0;
            });

            $total_stat_curr = [];
            foreach ($all_financial_stats as $e_f_exp => $total_inc) {
                $currency_ = Event::find($e_f_exp)->currency;
                if (array_key_exists($currency_, $total_stat_curr)) {
                    $total_stat_curr[$currency_] += $total_inc;
                } else {
                    $total_stat_curr[$currency_] = $total_inc;
                }

            }
            return $total_stat_curr;
        }
    function getNePerc($who,$event_ids){
        $all_financial_perc = Financial::all()->whereIn('event_id',$event_ids)->pluck($who, 'event_id')->filter(function ($value, $key) {
            return $value != null && $value != 0;
        });
        $total_perc_curr = [];

        foreach ($all_financial_perc as $pers_ => $total_inc) {
            $currency_ = Event::find($pers_)->currency;
            $total = Event::find($pers_)->financial->total;
            if (array_key_exists($currency_, $total_perc_curr)) {
                $total_perc_curr[$currency_] += ($total*($total_inc/100));
            } else {
                $total_perc_curr[$currency_] = ($total*($total_inc/100));
            }


        }
        return $total_perc_curr;
    }
    function getRewards($who,$event_ids){
        $all_financial_franch = Financial::all()->whereIn('event_id',$event_ids)->pluck('franch_percent', 'event_id')->filter(function ($value, $key) {
            return $value != null && !empty($value);
        });
        $total_rew_curr = [];

        foreach ($all_financial_franch as $e_f_exp => $total_inc) {
            // dd($total_inc);
            $currency_ = Event::find($e_f_exp)->currency;
            foreach ($total_inc as $sale) {

                if($sale['franch_status'] == $who){
                    if (array_key_exists($currency_, $total_rew_curr)) {
                        $total_rew_curr[$currency_] += $sale['full_sum'];
                    } else {
                        $total_rew_curr[$currency_] = $sale['full_sum'];
                    }
                }
            }
        }
        return $total_rew_curr;
    }
    function getSales($who,$event_ids){
        $all_financial_income = Financial::all()->whereIn('event_id',$event_ids)->pluck('income', 'event_id')->filter(function ($value, $key) {
            return $value != null && !empty($value);
        });
        $total_income_curr = [];

        foreach ($all_financial_income as $e_f_exp => $total_inc) {
            // dd($total_inc);
            $currency_ = Event::find($e_f_exp)->currency;
            $event_franch = Event::find($e_f_exp)->user_id;
            foreach ($total_inc as $sale) {
                // dd(Ticket::find($sale['ticket_id'])->seller_role_id);
                $cur_ticket = Ticket::find($sale['ticket_id']);
                if($who == 'eventAdmin'){
                    if($cur_ticket->seller_id == $event_franch){
                        if (array_key_exists($currency_, $total_income_curr)) {
                            $total_income_curr[$currency_] += $sale['sum'];
                        } else {
                            $total_income_curr[$currency_] = $sale['sum'];
                        }
                    }
                }
                else{
                    if($cur_ticket->seller_role_id == $who && $cur_ticket->seller_id != $event_franch){
                        if (array_key_exists($currency_, $total_income_curr)) {
                            $total_income_curr[$currency_] += $sale['sum'];
                        } else {
                            $total_income_curr[$currency_] = $sale['sum'];
                        }
                    }
                }
            }
        }
        return $total_income_curr;
    }
    public function EventAdminReportAll(Event $event)
    {

        if (Auth::user()->role_id == 3) {
            $events = Event::all();
            $events_ids = Event::all()->pluck('id');
        } else if (Auth::user()->role_id == 6) {
            $user_id = Auth::user()->id;
            $events = Event::where('user_id', $user_id)->get();
            $events_ids = Event::where('user_id', $user_id)->pluck('id');
        }

        $total_inc_curr = $this->getFinStats('total_income',$events_ids);
        $total_exp_curr = $this->getFinStats('total_consuption',$events_ids);
        $total_r_income_curr = $this->getFinStats('total_rawIncome',$events_ids);
        $total_disc_curr = $this->getFinStats('total_discount',$events_ids);
        $total_franch_curr = $this->getFinStats('franch_total',$events_ids);
        $total_net = $this->getFinStats('total',$events_ids);



        $net_Samo = $this->getNePerc('samo_percent',$events_ids);
        $net_Event = $this->getNePerc('event_percent',$events_ids);
        $net_Speaker = $this->getNePerc('speaker_percent',$events_ids);

        $partners_rew = $this->getRewards(5,$events_ids);
        $franch_rew = $this->getRewards(4,$events_ids) + $this->getRewards(6,$events_ids);
        $sales_rew = $this->getRewards(8,$events_ids);


        $partners_sales = $this->getSales(5,$events_ids) + $this->getSales(8,$events_ids);
        $SAMO_sales = $this->getSales(null,$events_ids);
        $EventAdmin_sales = $this->getSales('eventAdmin',$events_ids);
        $franch_sales = $this->getSales(4,$events_ids) + $this->getSales(6,$events_ids);


        return view('admin.reports.event_admin.report_all', [
            'all_events' => $events,
            'total_inc_curr' => $total_inc_curr,
            'total_exp_curr'=>$total_exp_curr,
            'total_r_income_curr'=>$total_r_income_curr,
            'total_disc_curr'=>$total_disc_curr,
            'total_franch_curr'=>$total_franch_curr,
            'net_Samo'=>$net_Samo,
            'net_Event'=>$net_Event,
            'net_Speaker'=>$net_Speaker,
            'total_net'=>$total_net,
            'partners_rew'=>$partners_rew,
            'franch_rew'=>$franch_rew,
            'sales_rew'=>$sales_rew,
            'partners_sales'=>$partners_sales,
            'SAMO_sales'=>$SAMO_sales,
            'EventAdmin_sales'=>$EventAdmin_sales,
            'franch_sales'=>$franch_sales,
        ]);
    }

    public function AjaxGetReport(Request $request)
    {

        $date1 = Carbon::createFromFormat('D M d Y H:i:s e+', $request->date1);
        $date2 = Carbon::createFromFormat('D M d Y H:i:s e+', $request->date2);
        $date2->add(1, 'day');/// + 1 day

        $currency_id = $request->currency_id;


        if (Auth::user()->role_id == 3) {
            $events = Event::all();
        } else if (Auth::user()->role_id == 6) {
            $user_id = Auth::user()->id;
            $events = Event::where('user_id', $user_id)->get();
        }

        $event_ids = [];
        foreach ($events as $event) {
            $event_date = Carbon::parseFromLocale($event->date, 'ru');
            if ($event_date >= $date1 && $event_date <= $date2) {
                array_push($event_ids, $event->id);
            }
        }


        $total_inc_curr = $this->getFinStats('total_income');
        $total_exp_curr = $this->getFinStats('total_consuption');
        $total_r_income_curr = $this->getFinStats('total_rawIncome');
        $total_disc_curr = $this->getFinStats('total_discount');
        $total_franch_curr = $this->getFinStats('franch_total');
        $total_net = $this->getFinStats('total');



        $net_Samo = $this->getNePerc('samo_percent');
        $net_Event = $this->getNePerc('event_percent');
        $net_Speaker = $this->getNePerc('speaker_percent');

        $partners_rew = $this->getRewards(5);
        $franch_rew = $this->getRewards(4) + $this->getRewards(6);
        $sales_rew = $this->getRewards(8);


        $partners_sales = $this->getSales(5) + $this->getSales(8);
        $SAMO_sales = $this->getSales(null);
        $EventAdmin_sales = $this->getSales('eventAdmin');
        $franch_sales = $this->getSales(4) + $this->getSales(6);
//////////////////////////////////////////////////////////////////////////////////////////////


        $simple_array = array_values($event_ids);
        $all_events = Event::findMany($simple_array);
        $view = view('admin.reports.event_admin.event_includes.all_report_body', [
            'all_events' => $all_events,
            'total_inc_curr' => $total_inc_curr,
            'total_exp_curr'=>$total_exp_curr,
            'total_r_income_curr'=>$total_r_income_curr,
            'total_disc_curr'=>$total_disc_curr,
            'total_franch_curr'=>$total_franch_curr,
            'net_Samo'=>$net_Samo,
            'net_Event'=>$net_Event,
            'net_Speaker'=>$net_Speaker,
            'total_net'=>$total_net,
            'partners_rew'=>$partners_rew,
            'franch_rew'=>$franch_rew,
            'sales_rew'=>$sales_rew,
            'partners_sales'=>$partners_sales,
            'SAMO_sales'=>$SAMO_sales,
            'EventAdmin_sales'=>$EventAdmin_sales,
            'franch_sales'=>$franch_sales,
        ])->render();
        return response()->json([
            'view' => $view,
        ]);


    }


    public function partnerReport()
    {
        $user = Auth::user();


    }

    public function partner_new() {
        $user = User::where('role_id',3)->first();

        return view('admin.franchise.create_partner')->with('user',$user);
    }

    public function partner_new_store(Request $request) {

        $ref_check = Cookie::has('part_ref');
        $part_ref_id = null;
        if ($ref_check) {
            $part_ref_id = request()->cookie('part_ref');
            $part_ref_link = partner_referral_link::find($part_ref_id);
            $franch_user = User::find($part_ref_link->user_id);
        }
        $franch_user_id = null;
        if ($franch_user) {
            if (in_array($franch_user->role_id, [4, 6])) {
                $franch_user_id = $franch_user->id;
            }
        }

        $name = $request->name;
        $last_name = $request->last_name;
        $middle_name = $request->middle_name;
        $city = $request->city;
        $country = $request->country;
        $contacts = $request->contacts;
        $inn = $request->inn;
        $email = $request->email;
        $contract_date = $request->date;
        $contract_date_end = $request->date_end;
        $contract = $request->contract;
        $comments = $request->comments;
        $pay_type = $request->pay_type;
        // $percent_from_sales = $request->franch_percent;// % от продаж билетов клиентам
        // $percent_from_partner = $request->percent_from_partner;// % от продаж партнера --- может быть только у франчайзи
        $user = new PendingUser();
        $request->validate([
            'email' => 'required|email|unique:users',
            'inn' => 'required|digits_between:10,14|unique:users',
            'contacts' => 'required|unique:users|min:8',
        ]);

        $user->email = $email;
        $user->name = $name;
        $user->last_name = $last_name;
        $user->city = $city;
        $user->country = $country;
        $user->phone = $contacts;
        $user->INN = $inn;
        $user->comments = $comments;
        $user->middle_name = $middle_name;
        $user->pay_type = $pay_type;

        // $public_path = public_path();
        // if($request->hasFile('contract')){
        //     $imageName_contract = request()->inn.'_'.'.'.request()->contract->getClientOriginalExtension();
        //     $path = $request->file('contract')->storeAs('public/images/contracts',$imageName_contract);
        //     $user->contract = $imageName_contract;
        // }
        $user->franchise_id = $franch_user_id;
        $user->role_id = 5;
        $user->p_ref_id = $part_ref_id;
        $user->paid = 0;
        $user->save();
        // $partner_ref_rel = new partner_referral_relationship();
        // $partner_ref_rel->partner_referral_link_id = $part_ref_id;
        // $partner_ref_rel->user_id = $user->id;
        // $partner_ref_rel->active = 0;
        // $partner_ref_rel->paid = 0;
        // $partner_ref_rel->save();

//////////////////////////////////////////////////////////////////////////////////////
      $now = Carbon::now()->format('Y-m-d H:i');
      $admin = User::where('role_id',3)->first();
      $idempotence = uniqid('', true);
      $host = request()->getSchemeAndHttpHost();
      if($request->pay_type=="yandex"){
          $client = new Client();
          $shopId = $admin->yandex_shop_id;
          $secretKey = $admin->yandex_secret_key;
          $client->setAuth($shopId, $secretKey);
          $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $admin->partner_sell_price,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => $host.'/check_certain_partner_sell/'.$user->id,
                ),
                'capture' => true,
                'description' => "Продажа партнерства",
                'metadata' => array(
                      'created_at' => $now,
                  ),
              ),
              $idempotence
          );
          $user->idempotence = $idempotence;
          $user->payment_id = $payment['_id'];
          $user->save();
          return redirect($payment['_confirmation']['_confirmationUrl']);

        }elseif($request->pay_type=="paybox"){
          $payboxMerchantId = $admin->paybox_merchant_id;
          $payboxSecretKey = $admin->paybox_secret_key;
          $requestForPayBox = [
              'pg_merchant_id'=> $payboxMerchantId,
              'pg_amount' => $admin->partner_sell_price,
              'pg_currency'=>"RUB",
              'pg_salt' => Str::random(16),
              'pg_order_id' => $idempotence,
              'pg_description' => 'Продажа партнерства',
              'pg_success_url'=>$host.'/paybox_success_partner_sell',
              'pg_failure_url'=>$host.'/paybox_failure_partner_sell',
              'pg_result_url'=>$host.'/paybox_result_partner_sell',
              'pg_testing_mode'=>1,
              'pg_lifetime'=>7200,
          ];
          ksort($requestForPayBox);
          array_unshift($requestForPayBox, 'payment.php');
          array_push($requestForPayBox, $payboxSecretKey);
          $requestForPayBox['pg_sig'] = md5(implode(';', $requestForPayBox));
          unset($requestForPayBox[0], $requestForPayBox[1]);
          $query = http_build_query($requestForPayBox);
          $user->idempotence =$idempotence;
          $user->save();
          return redirect('https://api.paybox.money/payment.php?'.$query);
        }else{
          session(['check' => 'admin_need_pay']);

          return redirect()->route('main');

        }

    }
    public function paybox_result_partner_sell(){
      $idempotence = $_GET['pg_order_id'];
      $result = $_GET['pg_result'];
      $pending = PendingUser::where('idempotence',$idempotence)->first();
      if($pending){
        if($result == 1){
          $admin = User::where('role_id',3)->first();
          $ran_pass = str_random(8);
          $user = new User();
          $user->email = $pending->email;
          $user->name = $pending->name;
          $user->last_name = $pending->last_name;
          $user->city = $pending->city;
          $user->country = $pending->country;
          $user->contacts = $pending->phone;
          $user->INN = $pending->INN;
          $user->comments = $pending->comments;
          $user->middle_name = $pending->middle_name;
          $user->franchise_id = $pending->franchise_id;
          $user->role_id = $pending->role_id;
          $user->p_ref_id=$pending->p_ref_id;
          $user->password = Hash::make($ran_pass);
          $user->save();

        $partner_ref_rel = new partner_referral_relationship();
        $partner_ref_rel->partner_referral_link_id = $pending->p_ref_id;
        $partner_ref_rel->user_id = $user->id;
        $partner_ref_rel->active = 0;
        $partner_ref_rel->paid = 1;
        $partner_ref_rel->pay_type = $pending->pay_type;
        $partner_ref_rel->save();


          $pending->delete();
          $log_url = url('/login');
          if ($user->email){
              Mail::to($user->email)->send(new FranchInvitation($user,$ran_pass,$log_url));
          }
        }
        if($result == 0){
          $pending->delete();
        }
      }
    }
    public function paybox_success_partner_sell(){
      $idempotence = $_GET['pg_order_id'];
      $pending = PendingUser::where('idempotence',$idempotence)->first();
      $now = Carbon::now()->format('Y-m-d H:i');
      if($pending){
        $admin = User::where('role_id',3)->first();
        $ran_pass = str_random(8);
        $user = new User();
        $user->email = $pending->email;
        $user->name = $pending->name;
        $user->last_name = $pending->last_name;
        $user->city = $pending->city;
        $user->country = $pending->country;
        $user->contacts = $pending->phone;
        $user->INN = $pending->INN;
        $user->comments = $pending->comments;
        $user->middle_name = $pending->middle_name;
        $user->franchise_id = $pending->franchise_id;
        $user->role_id = $pending->role_id;
        $user->p_ref_id=$pending->p_ref_id;
        $user->password = Hash::make($ran_pass);
        $user->save();

        $partner_ref_rel = new partner_referral_relationship();
        $partner_ref_rel->partner_referral_link_id = $pending->p_ref_id;
        $partner_ref_rel->user_id = $user->id;
        $partner_ref_rel->active = 0;
        $partner_ref_rel->paid = 1;
        $partner_ref_rel->pay_type = $pending->pay_type;
        $partner_ref_rel->save();

        $pending->delete();
        $log_url = url('/login');
        if ($user->email){
            Mail::to($user->email)->send(new FranchInvitation($user,$ran_pass,$log_url));
        }
      }
      session(['check' => 'partner_sell_succeeded']);
      return redirect()->route('main');

    }
    public function paybox_failure_partner_sell(){
      $idempotence = $_GET['pg_order_id'];
      $pending = PendingUser::where('idempotence',$idempotence)->first();
      $pending->delete();
      session(['check' => 'partner_sell_canceled']);
      return redirect()->route('main');
    }
    public function check_certain_partner_sell($id){
      $pending = PendingUser::find($id);
      session(['check' => 'partner_sell_succeeded']);
      if($pending){
        $admin = User::where('role_id',3)->first();
        $shopId = $admin->yandex_shop_id;
        $secretKey = $admin->yandex_secret_key;
        $paymentId = $pending->payment_id;
        $idempotenceKey = $pending->idempotence;
        $client = new Client();
        $client->setAuth($shopId, $secretKey);
        $payment = $client->getPaymentInfo($paymentId);
        if($payment['_status']=='canceled'){
          session(['check' => 'partner_sell_canceled']);
          $pending->delete();
        }else if($payment['_status']=='succeeded'){
          $ran_pass = str_random(8);
          $user = new User();
          $user->email = $pending->email;
          $user->name = $pending->name;
          $user->last_name = $pending->last_name;
          $user->city = $pending->city;
          $user->country = $pending->country;
          $user->contacts = $pending->phone;
          $user->INN = $pending->INN;
          $user->comments = $pending->comments;
          $user->middle_name = $pending->middle_name;
          $user->franchise_id = $pending->franchise_id;
          $user->role_id = $pending->role_id;
          $user->p_ref_id=$pending->p_ref_id;
          $user->password = Hash::make($ran_pass);
          $user->save();

        $partner_ref_rel = new partner_referral_relationship();
        $partner_ref_rel->partner_referral_link_id = $pending->p_ref_id;
        $partner_ref_rel->user_id = $user->id;
        $partner_ref_rel->active = 0;
        $partner_ref_rel->paid = 1;
        $partner_ref_rel->pay_type = $pending->pay_type;
        $partner_ref_rel->save();

          $pending->delete();
          $log_url = url('/login');
          if ($user->email){
              Mail::to($user->email)->send(new FranchInvitation($user,$ran_pass,$log_url));
          }
        }
      }
      return redirect()->route('main');

    }
    public function check_partner_sell(){
      $pending = PendingUser::all;
      $admin = User::where('role_id',3)->first();
      $shopId = $admin->yandex_shop_id;
      $secretKey = $admin->yandex_secret_key;
      $paymentId = $pending->payment_id;
      $idempotenceKey = $pending->idempotence;
      $client = new Client();
      $client->setAuth($shopId, $secretKey);
      $payment = $client->getPaymentInfo($paymentId);
      if($payment['_status']=='canceled'){
        session(['check' => 'partner_sell_canceled']);
        $pending->delete();
      }else if($payment['_status']=='succeeded'){
        session(['check' => 'partner_sell_succeeded']);
        $user = new User();
        $ran_pass = str_random(8);
        $user->email = $pending->email;
        $user->name = $pending->name;
        $user->last_name = $pending->last_name;
        $user->city = $pending->city;
        $user->country = $pending->country;
        $user->contacts = $pending->phone;
        $user->INN = $pending->INN;
        $user->comments = $pending->comments;
        $user->middle_name = $pending->middle_name;
        $user->franchise_id = $pending->franchise_id;
        $user->role_id = $pending->role_id;
        $user->p_ref_id=$pending->p_ref_id;
        $user->password = Hash::make($ran_pass);
        $user->save();

        $partner_ref_rel = new partner_referral_relationship();
        $partner_ref_rel->partner_referral_link_id = $pending->p_ref_id;
        $partner_ref_rel->user_id = $user->id;
        $partner_ref_rel->active = 0;
        $partner_ref_rel->paid = 1;
        $partner_ref_rel->pay_type = $pending->pay_type;
        $partner_ref_rel->save();

        $pending->delete();
        $log_url = url('/login');
          if ($user->email){
              Mail::to($user->email)->send(new FranchInvitation($user,$ran_pass,$log_url));
          }
      }
      return redirect()->route('main');

    }
    public function pending_change_paid(Request $request){
        $user_id = $request['user_id'];
        $pending = PendingUser::find($user_id);

        if($pending->paid==0){
            $pending->paid = 1;

            $pending->save();
            $ran_pass = str_random(8);
            $user = new User();
            $user->email = $pending->email;
            $user->name = $pending->name;
            $user->last_name = $pending->last_name;
            $user->city = $pending->city;
            $user->country = $pending->country;
            $user->contacts = $pending->phone;
            $user->INN = $pending->INN;
            $user->comments = $pending->comments;
            $user->middle_name = $pending->middle_name;
            $user->franchise_id = $pending->franchise_id;
            $user->role_id = $pending->role_id;
            $user->p_ref_id=$pending->p_ref_id;
            $user->password = Hash::make($ran_pass);
            $user->save();

            $partner_ref_rel = new partner_referral_relationship();
            $partner_ref_rel->partner_referral_link_id = $pending->p_ref_id;
            $partner_ref_rel->user_id = $user->id;
            $partner_ref_rel->active = 0;
            $partner_ref_rel->paid = 1;
            $partner_ref_rel->pay_type = $pending->pay_type;
            $partner_ref_rel->save();

            $pending->delete();
            $log_url = url('/login');
            if ($user->email){
              Mail::to($user->email)->send(new FranchInvitation($user,$ran_pass,$log_url));
            }

        }



        $ref_partners_list = PendingUser::all();
        $data=[
            "ref_partners_list"=>$ref_partners_list,
        ];
        return response()->json([
                'status' => $user->paid,
            ], 200);
        // return view('admin.users.include.ref_partners_list', ['data' => $data]);
    }
}
