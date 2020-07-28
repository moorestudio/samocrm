<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Event;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendCertificates;
use Illuminate\Support\Facades\Mail;
class CertificateController extends Controller
{
    public function create(Request $request)
    {
        $event = Event::find($request->event);
        $cerfDesign = Certificate::where('event_id',$request->event)->first();

        return view('admin.certificate.create', ['event' => $event, 'cerfDesign' => $cerfDesign]);

    }

    public function store(Request $request)
    {
        $cerfDesign = Certificate::where('event_id',$request->id)->first();
        if(!$cerfDesign)
        {
            $cerfDesign = new Certificate();
        }
        $title_style = collect(['content' => $request->title_content, 'font_size' => $request->title_font_size]);
        $date_style = collect(['font_size' => $request->date_font_size]);
        $name_style = collect(['font_size' => $request->user_font_size]);

        $cerfDesign->title_style = $title_style;
        $cerfDesign->date_style = $date_style;
        $cerfDesign->name_style = $name_style;
        $cerfDesign->event_id = $request->id;

        $cerfDesign->save();

        return response()->json([
            'status' => 'success',
        ]);
    }


    public function show($ticket)
    {
        $ticket = Ticket::find($ticket);
        $cerfDesign = Certificate::where('event_id',$ticket->event_id)->first();
        $event = Event::find($cerfDesign->event_id);
        $user = User::find($ticket->user_id);
        return view('admin.certificate.pdf',['cerfDesign' => $cerfDesign, 'event' => $event, 'user' => $user]);
    }
    public function sendCert(){
        $ticket_old = Ticket::find(14);
        $user = User::find($ticket_old->user_id);
        $event = Event::find($ticket_old->event_id);
        if($user){
            Mail::to($user->email)->send(new SendCertificates($ticket_old->id,$user->name,$event->title));
            $ticket_old->send_certificate = 1;
        }
    }
}
