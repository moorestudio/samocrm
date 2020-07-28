<?php

namespace App\Http\Controllers;
use App\Event;
use App\ReferralLink;
use App\partner_referral_link;
use Illuminate\Http\Request;
use Auth;


class ReflinkController extends Controller
{
    public function create(){
        $user_id = Auth::user()->id;
        $events = Event::where('user_id',$user_id)->get();
        // dd($events);
        return view('event.event_dashboard',['events' => $events]);
    }
    public function reff_gen(Request $request){
        $event_id = $request->event_id;
        $event = Event::find($event_id);
        if (!Auth::check() || \Auth::user()->role_id==2){
            //если гость или клиент

            if(isset(\Auth::user()->franchise_id) && \Auth::user()->role_id==2){
                //если клинет и есть франч
                
                $client_user = Auth::user();
                $client_franch_id=$client_user->franchise_id;
                $reff_link= ReferralLink::where('referral_event_id', $event_id)->where('user_id', $client_franch_id)->get()->first();
                if ($reff_link){
                    //если у франча клиента уже есть ссылка на это мероприятие
                    return response()->json([
                        'link'=>$reff_link->url_link,
                        'status' => 'success'
                    ]);
                }
                else{
                    //ели нет -> новая
                    ///new
                    $reff_link = new ReferralLink();

                    $reff_link->user_id=$client_franch_id;
                    $reff_link->referral_event_id=$event_id;
                    $reff_link->event_title = $event->title;
                    $reff_link->save();
                    ///new end
                    return response()->json([
                        'link'=>$reff_link->url_link,
                        'status' => 'success'
                    ]);
                }
            }
            else{
                //если гость или клиент без франча
                $url_link = url("info/".$event_id);
                return response()->json([
                    'link'=>$url_link,
                    'status' => 'success'
                ]);
            }
        }
        else{
            //все остальные
            $user_id = \Auth::user()->id;
            $reff_link= ReferralLink::where('referral_event_id', $event_id)->where('user_id', $user_id)->get()->first();
            if ($reff_link){
                //если уже есть ссылка
                return response()->json([
                    'link'=>$reff_link->url_link,
                    'status' => 'success'
                ]);

            }
            else{
                //ели нет -> новая
                ///new
                $reff_link = new ReferralLink();
                $reff_link->user_id=$user_id;
                $reff_link->referral_event_id=$event_id;
                $reff_link->event_title = $event->title;
                $reff_link->save();
                ///new end
                return response()->json([
                    'link'=>$reff_link->url_link,
                    'status' => 'success'
                ]);
            }



        }

    }


    public function part_reff_gen(Request $request){
        $user = Auth::user();
        if ($user->role_id!=2){
            $part_reff_link = new partner_referral_link();
            $part_reff_link->user_id=$user->id;
            $part_reff_link->save();
            return response()->json([
                'link'=>$part_reff_link->url_link,
                'status' => 'success',
            ]);

        }

    }











}
