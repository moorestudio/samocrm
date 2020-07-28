<?php

namespace App\Http\Controllers;

use App\List_option;
use App\User;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function search_client(Request $request)
    {
        $kind = $request->kind;
        $type = $request->type;
        $value = $request->value;

        $option = List_option::where('kind',$kind)->where('type', 1)->first();
//        dd($option->access);
        if($type == 1){
            if($value){
                $clients = User::where('name','like',"%$value%")->orWhere('last_name','like',"%$value%")->orWhere('email','like',"%$value%")->orWhere('contacts','like',"%$value%")->orWhere('middle_name','like',"%$value%")->pluck('id');
                if($clients){
                    $clients = User::where('role_id',2)->whereIn('id',$clients)->where('franchise_id', '!=', null)->paginate($option->user_per_page);
                }
            }else{
                $clients = User::where('role_id',2)->where('franchise_id', '!=', null)->paginate($option->user_per_page);
            }
            $clients->setPath('/clients_list');
            $view = view('admin.users.include.active_list', [
                'clients' => $clients,
                'option' => $option
            ])->render();
        }
        else if($type == 2){
            
            if($value){
                $clients = User::where('name','like',"%$value%")->orWhere('last_name','like',"%$value%")->orWhere('email','like',"%$value%")->orWhere('contacts','like',"%$value%")->orWhere('middle_name','like',"%$value%")->pluck('id');
                if($clients){
                    $clients = User::where('role_id',2)->whereIn('id',$clients)->where('franchise_id', null)->paginate($option->user_per_page, ["*"],'inactive_page');
                }

            }else{
                $clients = User::where('role_id',2)->where('franchise_id', null)->paginate($option->user_per_page, ["*"],'inactive_page');
            }
            $clients->setPath('/clients_list');
            $view = view('admin.users.include.inactive_list', [
                'inactive_clients' => $clients,
                'option' => $option
            ])->render();
        }
        return response()->json([
            'view' => $view,
        ]);
    }

    public function save_active_client(Request $request)
    {
        $option = List_option::find($request->id);
        $fullname = $request->fullname;
        $email = $request->email;
        $contacts = $request->contacts;
        $city = $request->city;
        $country = $request->country;
        $promo_name = $request->promo_name;
        $promo_discount = $request->promo_discount;
        $q_bought = $request->q_bought;

//        dd($request->all());

        $temp1 = $option['access'];
        $temp_option1 = [];
        $temp2 = $temp1[1];
        $temp2['active'] = $fullname;
        if ($fullname == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[1] = $temp2;
        $temp2 = $temp1[2];
        $temp2['active'] = $email;
        if ($email == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[2] = $temp2;
        $temp2 = $temp1[3];
        $temp2['active'] = $contacts;
        if ($contacts == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[3] = $temp2;
        $temp2 = $temp1[4];
        $temp2['active'] = $city;
        if ($city == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[4] = $temp2;
        $temp2 = $temp1[5];
        $temp2['active'] = $country;
        if ($country == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[5] = $temp2;
        $temp2 = $temp1[6];
        $temp2['active'] = $promo_name;
        if ($promo_name == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[6] = $temp2;
        $temp2 = $temp1[7];
        $temp2['active'] = $promo_discount;
        if ($promo_discount == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[7] = $temp2;
        $temp2 = $temp1[8];
        $temp2['active'] = $q_bought;
        if ($q_bought == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[8] = $temp2;
        $option['options'] = $temp_option1;
        $option['access'] = $temp1;
        $option->save();



//        dd($option['options']);
        session(['optionChanged' => 'success']);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function search_seller(Request $request)
    {
        $kind = $request->kind;
        $type = $request->type;
        $value = $request->value;

        $option = List_option::where('kind',$kind)->where('type', 1)->first();
//        dd($option->access);
        if($type == 1){
            if($value){
                $franchise_all = User::where('name','like',"%$value%")->orWhere('last_name','like',"%$value%")->orWhere('middle_name','like',"%$value%")->orWhere('email','like',"%$value%")->orWhere('contacts','like',"%$value%")->pluck('id');
                if($franchise_all){
                    $franchise_all = User::whereIn('role_id',[4,6])->whereIn('id',$franchise_all)->where('status',null)->paginate($option->user_per_page);
                }

            }else{
                $franchise_all = User::whereIn('role_id',[4,6])->where('status',null)->paginate($option->user_per_page);
            }
            $franchise_all->setPath('/user_list');

            $data = [
                "franchise_all"=>$franchise_all,
            ];
            $view = view('admin.users.include.franch_list', [
                'data' => $data,
                'option' => $option
            ])->render();
        }
        else if($type == 2){
            if($value){
                $partners_all = User::where('name','like',"%$value%")->orWhere('last_name','like',"%$value%")->orWhere('middle_name','like',"%$value%")->orWhere('email','like',"%$value%")->orWhere('contacts','like',"%$value%")->pluck('id');
                if($partners_all){
                    $partners_all = User::where('role_id',5)->whereIn('id',$partners_all)->where('status',null)->paginate($option->user_per_page, ["*"],'partner_page');
                }

            }else{
                $partners_all = User::where('role_id',5)->where('status',null)->paginate($option->user_per_page, ["*"],'partner_page');
            }
            $partners_all->setPageName('partner_page');
            $partners_all->setPath('/user_list');

            $data = [
                "partners_all"=>$partners_all,
            ];
            $view = view('admin.users.include.partner_list', [
                'data' => $data,
                'option' => $option
            ])->render();
        }

        else if($type == 3){
            if($value){
                $sales_all = User::where('name','like',"%$value%")->orWhere('last_name','like',"%$value%")->orWhere('middle_name','like',"%$value%")->orWhere('email','like',"%$value%")->orWhere('contacts','like',"%$value%")->pluck('id');
                if($sales_all){
                    $sales_all = User::where('role_id',8)->whereIn('id',$sales_all)->where('status',null)->paginate($option->user_per_page, ["*"],'sale_page');
                }

            }else{
                $sales_all = User::where('role_id',8)->where('status',null)->paginate($option->user_per_page, ["*"],'sale_page');
            }
            $sales_all->setPageName('sale_page');
            $sales_all->setPath('/user_list');

            $data = [
                "sales_all"=>$sales_all,
            ];
            $view = view('admin.users.include.sales_list', [
                'data' => $data,
                'option' => $option
            ])->render();
        }
        return response()->json([
            'view' => $view,
        ]);
    }

    public function save_franch_option(Request $request)
    {
        $option = List_option::find($request->id);
        $fullname = $request->fullname;
        $email = $request->email;
        $contacts = $request->contacts;
        $city = $request->city;
        // $country = $request->country;
        // $company = $request->company;
        $job = $request->job;
        $inn = $request->inn;
        $contract_start = $request->contract_start;
        $contract_end = $request->contract_end;

        $temp1 = $option['access'];
        $temp_option1 = [];
        $temp2 = $temp1[1];
        $temp2['active'] = $fullname;
        if ($fullname == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[1] = $temp2;
/////////////////////////////
        $temp2 = $temp1[2];
        $temp2['active'] = $email;
        if ($email == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[2] = $temp2;
        $temp2 = $temp1[3];
        $temp2['active'] = $contacts;
        if ($contacts == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[3] = $temp2;
        $temp2 = $temp1[4];
        $temp2['active'] = $city;
        if ($city == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[4] = $temp2;
        // $temp2 = $temp1[5];
        // $temp2['active'] = $country;
        // if ($country == 'true')
        // {
        //     array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        // }
        // $temp1[5] = $temp2;
        // $temp2 = $temp1[6];
        // $temp2['active'] = $company;
        // if ($company == 'true')
        // {
        //     array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        // }
        // $temp1[6] = $temp2;


        $temp2 = $temp1[5];
        $temp2['active'] = $job;
        if ($job == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[5] = $temp2;
        $temp2 = $temp1[6];
        $temp2['active'] = $inn;
        if ($inn == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[6] = $temp2;
        $temp2 = $temp1[7];
        $temp2['active'] = $contract_start;
        if ($contract_start == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[7] = $temp2;
        $temp2 = $temp1[8];
        $temp2['active'] = $contract_end;
        if ($contract_end == 'true')
        {
            array_push($temp_option1, ['option' => $temp2['option'], 'slug' => $temp2['slug']]);
        }
        $temp1[8] = $temp2;
        $option['options'] = $temp_option1;
        $option['access'] = $temp1;
        $option->save();



//        dd($option['options']);

//        $clients = User::where('role_id',2)->where('franchise_id', null)->get();
//        $view2 = view('admin.users.include.inactive_list', [
//            'clients' => $clients,
//            'option' => $option
//        ])->render();
        session(['optionChanged' => 'success']);

        return response()->json([
            'status' => 'success',
        ]);
    }
    public function setUserPerPage(Request $request){
        $option = List_option::where('kind','franch')->first();
        $option->user_per_page = $request->perPage;
        $option->save();
        return response()->json([
            'status' => 'success',
        ]);   
    }
    public function setClientPerPage(Request $request){
        $option = List_option::where('kind','client')->first();
        $option->user_per_page = $request->perPage;
        $option->save();
        return response()->json([
            'status' => 'success',
        ]);   
    }
}
