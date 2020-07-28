<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use AmoCRM\Client\AmoCRMApiClient;
use App\Amocrm;
use App\User;
use App\Event;
use App\Ticket;

class AMOController extends Controller
{
    public static function createAmoClient(User $user){
        Amocrm::refreshAMOCRMToken();
        $amo = Amocrm::all()->first();
        $catalog_id = 2159;
        $today = Carbon::now()->timestamp;
        $headers = [
            'Authorization: Bearer ' . $amo->access_token
        ];
        $request = [
            [
                'name'=> $user->fullName(),
                "custom_fields_values" => [
                    [
                        "field_id" => 623617,
                        "values" => [
                            [
                                "value" => $user->city,
                            ]
                        ]
                    ],
                    [
                        "field_id" => 256859,
                        "values" => [
                            [
                                "value" =>  $user->email,
                            ]
                        ]
                    
                    ],
                    [
                        "field_id" => 256861,
                        "values" => [
                            [
                                "value" => $user->contacts,
                            ]
                        ]
                    ],
                ]
            ]
        ];
        if($user->franchise_id){
            $franchise = User::find($user->franchise_id);
            if($franchise){
                $request[0]["responsible_user_id"] = $franchise->amo_id;
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://worldsamo.amocrm.ru/api/v4/contacts');
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($request));
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response_json, true);
        if(array_key_exists('_embedded',$response)){
            $user->amo_id = $response['_embedded']['contacts'][0]['id'];
            $user->save();
        }
    }

    public static function addDeal(User $client, Event $event, Ticket $ticket){
        Amocrm::refreshAMOCRMToken();
        $amo = Amocrm::all()->first();

        $today = Carbon::now()->timestamp; 
        $headers = [
            'Authorization: Bearer ' . $amo->access_token
        ];
        $request = [
            [
                "name"=> $event->title,
                "status_id"=>8959735,
                "pipeline_id" => 1412959,
                "price" => intval($ticket->ticket_price),
            ]
        ];
        if($client->franchise_id){
            $franchise = User::find($client->franchise_id);
            $samo = User::where('role_id',3)->first();

            if($franchise){
                $request[0]['responsible_id'] =  $franchise->amo_id;
            }elseif($samo){
                $request[0]['responsible_id'] =  $samo->amo_id;
            }

        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://worldsamo.amocrm.ru/api/v4/leads');
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($request));
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response_json, true);

        //link lead with contact
        if($client->amo_id and array_key_exists('_embedded',$response)){
            $lead_url = $response['_embedded']['leads'][0]['_links']['self']['href'];
            $request = [
                [
                    "to_entity_id"=> $client->amo_id,
                    "to_entity_type"=> "contacts",
                    "metadata"=> [
                        "is_main"=> true,
                    ]
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$lead_url.'/link');
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
            curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,CURLOPT_HEADER, false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($request));
            $response_json = curl_exec($ch);
            curl_close($ch);
        }
    }
}