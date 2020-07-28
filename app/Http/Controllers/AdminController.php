<?php

namespace App\Http\Controllers;
use App\List_option;
use Illuminate\Http\Request;
use App\Event;
use App\Ticket;
use App\LostClients;
use App\User;
use App\Role;
use App\PendingUser;
use App\DeletedUsers;
use App\Category;
use App\UsersActivity;
use App\ReferralLink;
use App\ReferralRelationship;
use App\partner_referral_relationship;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use App\Mail\EmailVerificationMailAble;
use App\Exports\UsersExport;
use App\Exports\UsersExport_F;
use App\Exports\UsersExport_P;
use App\Exports\UsersExport_S;
use App\Exports\ClientsExport;
use App\Exports\ClientsExport_With;
use App\Exports\ClientsExport_Without;
use App\Exports\ClientsExport_Event;
use App\Exports\AttendanceExport;
use App\Exports\AllAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\ArrayToXml\ArrayToXml;
use DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $events = Event::all()->sortBy('normal_date');
        $category = 0;
        if($request->id){
          $category = $request->id;
          $events = $events->where('category_id',$request->id);
        }
        return view('event.event_dashboard',['events' => $events,'category'=>$category]);
    }

    public function category(){
      $categories = Category::all();
      return view('admin.categories.category_page')->with('categories',$categories);
    }
    public function create_category()
    {
        return view('admin.categories.create_category');
    }

    public function edit_category(Request $request)
    {
        $category = Category::find($request->id);

        return view('admin.categories.create_category',['category' => $category]);
    }

    public function store_category(Request $request){
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->name;
        $category->save();
        return redirect()->route('category');
    }

    public function update_category(Request $request){
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('category');
    }
    public function delete_category(Request $request){
        $category = Category::find($request->id);
        $events = Event::where('category_id',$category->id)->get();
        foreach($events as $event){
          $event->category_id = null;
          $event->save();
        }
        $category->delete();
        return redirect()->route('category');
    }

    public function user_activity_statistics(){
      $activities = UsersActivity::all();
      return view('admin.users.user_activity')->with('activities',$activities);
    }

    public function user_list(){
        $option = List_option::where('kind','franch')->first();

        $franchise_all = User::whereIn('role_id',[4,6])->where('status',null)->paginate($option->user_per_page);
        $partners_all = User::where('role_id',5)->where('status',null)->paginate($option->user_per_page, ["*"],'partner_page');
        $partners_all->setPageName('partner_page');

        $sales_all = User::where('role_id',8)->where('status',null)->paginate($option->user_per_page, ["*"],'sale_page');
        $sales_all->setPageName('sale_page');

        $clients_all = User::where('role_id',2)->paginate($option->user_per_page, ["*"],'client_page');
        $clients_all->setPageName('client_page');

        $blocked_users = User::all()->where('status','blocked');
        $ref_partners_list = User::where('p_ref_id',!null)->paginate($option->user_per_page, ["*"],'ref_page');
        $ref_partners_list->setPageName('ref_page');

        $pending_users_list = PendingUser::paginate($option->user_per_page, ["*"],'pending_page');
        $pending_users_list->setPageName('pending_page');

        // dd($pending_users_list);
        $data = [
            "franchise_all"=>$franchise_all,
            "partners_all"=>$partners_all,
            "sales_all"=>$sales_all,
            "clients_all" => $clients_all,
            "blocked_users" => $blocked_users,
            "ref_partners_list"=>$ref_partners_list,
            "pending_users_list"=>$pending_users_list,
        ];
//        @dd($option->access);
        return view('admin.users.user_list',['data' => $data]);
    }

    public function clients_list()
    {
        $option = List_option::where('kind','client')->first();

        $clients = User::where('role_id',2)->where('franchise_id','!=',null)->where('status',null)->paginate($option->user_per_page);
        $inactive_clients = User::where('role_id',2)->where('franchise_id',null)->where('status',null)->paginate($option->user_per_page, ["*"],'inactive_page');

        return view('admin.users.clients_list',['clients' => $clients,'inactive_clients' => $inactive_clients]);

    }
    public function change_franch_event_rights(Request $request){
        $franch = User::find($request['id']);
        if(!$franch->event_rights){
            $franch->event_rights = 1;
            $franch->role_id = 6; //Event Franchise
        }
        else{
            $franch->event_rights = null;   
        }
        
        $franch->save();
    }



    public function archive()
    {
        // $franchise_all = User::all()->where('role_id',4)->where('status',null);
        // $partners_all = User::all()->where('role_id',5)->where('status',null);
        // $clients_all = User::all()->where('role_id',2);
        $blocked_users = User::all()->where('status','blocked');
        // dd($franchise_all);
        $data = [
            // "franchise_all"=>$franchise_all,
            // "partners_all"=>$partners_all,
            // "clients_all" => $clients_all,
            "blocked_users" => $blocked_users,
        ];

        return view('admin.users.archive',['data' => $data]);
    }
    public function delete_from_archive(Request $request)
        {
            $selected_users_array_archive = $request['selected_users_array_archive'];
            $selected_users_array_archive_cleared=[];
            foreach ($selected_users_array_archive as $value) {
                array_push($selected_users_array_archive_cleared, explode("_", $value)[1]);
            }
        ///если продажник нужно удалить его данные с таблиц: referral_links и referral relationship
        /// и обнулить franchise_id
            foreach($selected_users_array_archive_cleared as $id){
//                $user = User::where('id',$id);
                $user = User::find($id);
                //если клиент
                if ($user->role_id == 2){
                    $deleted_user  = new DeletedUsers;
                    $deleted_user->name = $user->fullName();
                    $deleted_user->user_id = $user->id;
                    $deleted_user->status = Role::find($user->role_id)->display_name;
                    $deleted_user->email = $user->email;
                    $deleted_user->save();
                    $user->delete();
                }
                // elseif ($user->role_id == 4 || $user->role_id == 5 || $user->role_id == 6){
                else {
                    /// Обнулить франч id
                    $all_users_franch = User::all()->where('franchise_id',$user->id);
                    if($all_users_franch){
                        foreach ($all_users_franch as $u) {
                            $u->franchise_id = null;
                            $u->save();
                            // Если клиент то он становится не закрепленным, 
                            // if($u->role_id == 2){
                            //     $u->franchise_id = null;    
                            // }
                            // else{
                            //     $u->franchise_id = Auth::user()->id; 
                            // }
                            
                        }
                    }
                    // Создать м
                    $deleted_user  = new DeletedUsers;
                    $deleted_user->name = $user->fullName();
                    $deleted_user->user_id = $user->id;
                    $deleted_user->status = Role::find($user->role_id)->display_name;
                    $deleted_user->email = $user->email;
                    $deleted_user->save();

                    // Если event admin
                    if($user->role_id == 6){
                        $user_events = Event::all()->where('user_id',$user->id);
                        foreach ($user_events as $e) {
                            $e->user_id = Auth::user()->id;
                            $e->save();
                        }
                    }
                    // И накнец удаляем пользователя
                    $user->delete();
                }
            }
        }

    public function blockFranch (Request $request){
        $selected_users_array = $request['selected_users_array'];
        foreach($selected_users_array as $id){
            $user = User::find($id);
            $user->status="blocked";
            $user->save();
            if(Auth::check()){
              $activity = new UsersActivity;
              $activity->date = Carbon::now()->format('Y-m-d');
              $activity->time = Carbon::now()->format('H:i:s');
              $activity->user_id = $user->id;
              $activity->admin_id = auth()->user()->id;
              $activity->type = 'block';
              $activity->save();
            }
        }


    }
    public function unblockFranch (Request $request){
        $selected_users_array_archive = $request['selected_users_array_archive'];
        $selected_users_array_archive_cleared=[];
        foreach ($selected_users_array_archive as $value) {
            array_push($selected_users_array_archive_cleared, explode("_", $value)[1]);
        }

        // dd($selected_users_array_archive_cleared);
        foreach($selected_users_array_archive_cleared as $id){
            $user = User::find($id);
            $user->status=null;
            $user->save();
            if(Auth::check()){
              $activity = new UsersActivity;
              $activity->date = Carbon::now()->format('Y-m-d');
              $activity->time = Carbon::now()->format('H:i:s');
              $activity->user_id = $user->id;
              $activity->admin_id = auth()->user()->id;
              $activity->type = 'active';
              $activity->save();
            }
        }

    }

    public function user_change_franch(Request $request)
    {
        $user = User::find($request->user_id);
        if($user->franchise_id)
        {
            $lost_client = new LostClients();
            $lost_client->franch_id = $user->franchise_id;
            $lost_client->client_id = $user->id;
            $lost_client->date = Carbon::now();
            $lost_client->save();
        }
        $user->franchise_id = $request->franch_id;
        $user->save();

        return back();
    }

    public function partner_sell_data(Request $request){
        $user = User::find($request->user_id);
        $user->yandex_shop_id = $request->yandex_shop_id;
        $user->yandex_secret_key = $request->yandex_secret_key;
        $user->paybox_merchant_id = $request->paybox_merchant_id;
        $user->paybox_secret_key = $request->paybox_secret_key;
        $user->partner_sell_price = $request->partner_sell_price;
        $user->save();
        return back();
    }

    // public function email_sent(Request $request) {
    //     $user = User::find(Auth::user()->id);
    //     $user_email = $user->email;
    //     return view('auth.verify_email',['user_email' => $user_email]);
    // }
    // public function email_re_send(Request $request) {
    //     $user = User::find(Auth::user()->id);
    //     $user_email = $user->email;
    //     $random_pass = str_random(8);

    //     $user->password = Hash::make($random_pass);
    //     $user->save();
    //     $re = True;
    //     Mail::to($user_email)->send(new EmailVerificationMailAble($user,$random_pass));
    //     return view('auth.verify_email',['user_email' => $user_email,'re'=>$re]);
    // }

    public function export_users()
    {
        return Excel::download(new UsersExport, 'excel_all.xls');

    }
    public function export_users_franch()
    {
        return Excel::download(new UsersExport_F, 'excel_franchise.xls');
    }
    public function export_users_partners()
    {
        return Excel::download(new UsersExport_P, 'excel_partners.xls');

    }
    public function export_users_sales()
    {
        return Excel::download(new UsersExport_S, 'excel_sales.xls');

    }

    public function export_clients()
    {
        return Excel::download(new ClientsExport, 'excel_clients_all.xls');

    }
    public function export_clients_with()
    {
        return Excel::download(new ClientsExport_With, 'excel_clients_f.xls');
    }
    public function export_clients_without()
    {
        return Excel::download(new ClientsExport_Without, 'excel_clients_0.xls');

    }

    public function export_clients_event($id)
    {
      return Excel::download(new ClientsExport_Event($id), 'excel_client_event.xls');


    }

    public function export_attendance($id)
    {

      return Excel::download(new AttendanceExport($id), 'excel_client.xls');


    }
    public function export_all_events_attendance(Request $request)
    {

      return Excel::download(new AllAttendanceExport($request->start,$request->end), 'export_all_attendance.xls');
    }
    public function export_attendance_xml($id)
    {
      $event = Event::find($id);
      $tickets = Ticket::where('event_id',$event->id)->get();
      $all_ticket = intval($event->client_count);
      $buy_ticket = count($tickets->whereIn('type',['done','buy']));
      $price = $tickets->whereIn('type',['done','buy'])->sum('ticket_price');
      $data = ['По мероприятию'=>
        ['Проект'=>$event->title,'Дата начало'=>$event->date,'Дата конец'=>$event->end_date,
        'Общ.кол билетов'=>$all_ticket,'Продано билетов'=>$buy_ticket,'Сумма продаж билетов'=>$price,
        'Не проданые билеты'=>$all_ticket - $buy_ticket,'Спикер'=>$event->mentor, 'Город'=>$event->city,
        'Адрес'=>$event->address],
      ];

      foreach($tickets as $ticket){
        $user = User::find($ticket->user_id);
        if($user){
          $franchise = User::find($user->franchise_id);
          $participate = "Нет";
          $franchise_role = "";
          if($franchise){
            $franchise = $franchise->name;
            $franchise_role = User::find($user->franchise_id)->role->display_name;
          }else{
            $franchise = "";
          }
          if($ticket->type == "done"){
            $participate = "Да";
          }
          $data['Клиент'.$ticket->id] = ['ФИО клиент'=>$user->name,'Продавец'=>$franchise,'Роль продавца'=>$franchise_role,
          'Участвовал'=>$participate];
        }
      }
      $filename = $event->title.'_выгрузка'.'.xml';
      $result = ArrayToXml::convert($data,'Посещаемость',true,'UTF-8');
      $result = Response::create($result, 200);
      $result->header('Content-Type', 'text/xml');
      $result->header('Cache-Control', 'public');
      $result->header('Content-Description', 'File Transfer');
      $result->header('Content-Disposition', 'attachment; filename=' . $filename . '');
      $result->header('Content-Transfer-Encoding', 'binary');
      return $result;
    }



    public function switch_event(Request $request)
    {
       // $events = Event::all()->sortByDesc('created_at');
        $id=Auth::user()->id;

        $reff_links_event_id_url = ReferralLink::all()->where('user_id',$id)->pluck('url_link','referral_event_id');
        $reff_links_event_ids = ReferralLink::all()->where('user_id',$id)->pluck('referral_event_id');
        // $events = Event::where('active',1)->where('date', '>' , Carbon::now())->get()->sortByDesc('created_at');
        $events_t = Event::all()->sortByDesc('created_at');
        $events_with_links = Event::find($reff_links_event_ids);
        $diff = $events_t->diff($events_with_links);
        foreach ($events_with_links as $event)
            foreach ($reff_links_event_id_url as $key => $value)
                if ($event->id == $key)
                    $event['link_new']=$value;
        $events=$events_with_links->merge($diff);

        // dd($events);

        $current_user_events=[];
        foreach ($events as $eve){
            if($eve->user_id == Auth::user()->id && Auth::user()->role_id == 6){
                array_push($current_user_events, $eve);
            }
        }

        // if($request->category){
        //   $events = $events->where('category_id',$request->category);
        // }
        // dd($request);
        if($request->id == 1)
        {
            if($request->who == "this"){
                // dd($current_user_events);
                $view = view('event.admin.includes.events_active_this', ['events' => $events,'current_user_events'=>$current_user_events])->render();
            }
            elseif($request->who == "this_all"){
                $view = view('event.admin.includes.events_active', ['events' => $events,])->render();
            }
            else{
                $view = view('event.admin.includes.events_active', ['events' => $events,])->render();
            }

        }
        else
        //Past
        {




            if($request->who == "this"){
                // dd($current_user_events);
                $view = view('event.admin.includes.events_inactive_this', ['events' => $events,'current_user_events'=>$current_user_events])->render();
            }
            elseif($request->who == "this_all"){
                $view = view('event.admin.includes.events_inactive', ['events' => $events,])->render();
            }
            else{
                // dd($current_user_events);
                $view = view('event.admin.includes.events_inactive', ['events' => $events,])->render();
            }
        }

        return response()->json([
            'view' => $view,
        ]);
    }
    public function switch_event_report(Request $request)
    {
        if(\Illuminate\Support\Facades\Auth::user()->id == 1){
        $events = Event::all();
        }
        else
        {
            $events = Event::where('user_id',\Illuminate\Support\Facades\Auth::user()->id)->get();
        }
        if($request->id == 1)
        {
            $view = view('admin.reports.event_admin.event_includes.events_active', [
                'events' => $events,
            ])->render();
        }
        else
        {
            $view = view('admin.reports.event_admin.event_includes.events_inactive', [
                'events' => $events,
            ])->render();
        }

        return response()->json([
            'view' => $view,
        ]);
    }



    public function search_client_ajax(Request $request){
      $search = $request->search;  
      if($request->ajax()){
        // if(Auth::user()->role_id == 3){}
        if($search !=null){
            $users_all=DB::table('users')
                ->where('users.role_id' ,'=', 2)
                ->where(function($query) use ($search){
                    $query->where('users.name', 'LIKE', '%'.$search.'%');
                    $query->orWhere('users.last_name', 'LIKE', '%'.$search.'%');
                    $query->orWhere('users.middle_name', 'LIKE', '%'.$search.'%');
                    $query->orWhere('users.email', 'LIKE', '%'.$search.'%');
                    $query->orWhere('users.contacts', 'LIKE', '%'.$search.'%');
                })->get();
            }
        else{
            $users_all=DB::table('users')
                ->where('users.role_id' ,'=', 2)->get();
        }
        return response()->json([
        'view' => view('event.buy.event_includes.old_user_data_render')->with('users',$users_all)->render(),
        ], 200);
      }
    }
    public function get_user_data(Request $request){
        if($request->ajax()){
            $user = User::find($request->id);
        return response()->json([
            'user' => $user,
        ]);
        }
    }

    public function save_user_ajax(Request $request){

        if($request['work_type'] !== '6'){
            $request['other_work_type'] = '';
        }
        $data=$request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            'contacts' => ['required', 'string', 'min:8', 'unique:users'],// судя по всему это к contacts
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'job' => ['required', 'string'],
            'company' => ['required', 'string'],
            'work_type' => ['required', 'string'],
            'other_work_type' => ['string'],

        ]);


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

        if($request->send_email==1){
            Mail::to($user->email)->send(new EmailVerificationMailAble($user,$random_pass));
        }

        return response()->json([
        'user' => $user,
        ], 200);

    }

    public function getUserActivityReport(Request $request){
      if($request->start){
        $start = Carbon::parseFromLocale($request->start,'ru')->addDays(1)->format('Y-m-d');
        $end = Carbon::parseFromLocale($request->end,'ru')->addDays(1)->format('Y-m-d');
        $activities = UsersActivity::whereBetween(DB::raw('DATE(date)'), array($start, $end))->get();
      }else{
        $activities = UsersActivity::all();
      }
      if($request->user){
        $activities = $activities->where('user_id',$request->user);
      }
      $visit_q = $activities->where('type','visit')->count();
      $active_q = $activities->where('type','active')->count();
      $block_q = $activities->where('type','block')->count();
      $visit = view('admin.users.include.user_activity_visit')->with('activities',$activities)->render();
      $action = view('admin.users.include.user_activity_block_active')->with('activities',$activities)->render();
      return response()->json([
        'visit' => $visit,
        'action' => $action,
        'visit_q' => $visit_q,
        'active_q' => $active_q,
        'block_q' => $block_q,
      ], 200);

    }
    public function getUserWithRole(Request $request){
      $search = $request->user;
      if($request->role){
        $result = collect(['Пользователи' => User::where('role_id',$request->role)->where('name','like', "%$search%")->get()]);
      }else{
        $result = collect(['Пользователи' => User::where('role_id','!=',3)->where('name','like', "%$search%")->get()]);
      }
      $count = count($result);
      return response()->json([
          'status'=>'success',
          'users'=>view('modals.users.user_result')->with('result',$result)->with('count',$count)->render(),
      ]);
    }
    public function ajax_set_new_reserve_date(Request $request){
        // dd($request);
        $date= Carbon::createFromFormat('D M d Y H:i:s e+', $request->new_date);
        // dd($date);
        $ticket = Ticket::find($request->id);

        // dd($ticket);
        $ticket->reserve_date = $date;
        $ticket->save();
        return response()->json([
            'status' => 'success'
        ]);
    }

}
