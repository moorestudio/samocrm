<?php

namespace App\Http\Controllers;

use App\Event;
use App\History;
use App\Ticket;
use App\Certificate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    //
    public function scanner_choice()
    {
        $events = Event::where('active',1)->get();
        return view('pwa.ticket.list',['events' => $events]);
    }

    public function scanner($id)
    {
        return view('pwa.ticket.scanner',['id' => $id]);
    }


    public function scanner_check(Request $request)
    {
        $content = $request->qr;
        $content = str_replace('ticket', '', $content);
        $content = (int)$content;
        $ticket = Ticket::where('id',$content)->where('event_id',$request->event_id)->first();

        if(isset($ticket) && $ticket->type == 'buy' )
        {
            $user = User::find($ticket->user_id);
            $user = $user->fullname();
            if ($request->ajax()){
                return response()->json([
                    'status' => "success",
                    'check' => 1,
                    'ticket_id' => $ticket->id,
                    'user' => $user,
                ], 200);
            }
        }
        else if (isset($ticket) && $ticket->type == 'done')
        {
            if ($request->ajax()){
                return response()->json([
                    'status' => "error",
                    'check' => 2,
                    'ticket_id' => $ticket->id,
                ], 200);
            }
        }
        else
        {
            if ($request->ajax()){
                return response()->json([
                    'status' => "error",
                    'check' => 0,
                ], 200);
            }
        }
    }

    public function scanner_find(Request $request)
    {
        $content = $request->qr;
        $content = str_replace('ticket', '', $content);
        $content = (int)$content;
        $ticket = Ticket::where('id',$content)->where('event_id',$request->event_id)->first();

        if($ticket->type == 'buy')
        {

            $user = User::find($ticket->user_id);

            // $event = Event::find($ticket->event_id);
            // $cerfDesign = Certificate::where('event_id',$ticket->event_id)->first();
            // $pdf = PDF::loadView('admin.certificate.pdf', ['cerfDesign' => $cerfDesign, 'event' => $event]);
            // $path = $event->title . '-'.$user->id. str_replace(' ','-',$user->name).Str::random(10). '.pdf';
            // Storage::put('public/cerfpdf/' .$path, $pdf->output());
            // $ticket->certificate_img = $path;


            $ticket->type = 'done';
            $ticket->save();
          if ($request->ajax()){
              return response()->json([
                  'status' => "success",
                  'check' => 1,
                  'ticket_id' => $ticket->id,
              ], 200);
          }
        }
        else
        {
            if ($request->ajax()){
                return response()->json([
                    'status' => "error",
                    'check' => 0,
                    'ticket_id' => $ticket->id,
                ], 200);
            }
        }
    }

    public function scanner_error()
    {
        return view('pwa.ticket.scanner_error');
    }

    public function scanner_success()
    {
//        $ticket = Ticket::find($request->ticket_id);
        return view('pwa.ticket.scanner_success');
    }

    public function scanner_done()
    {
        return view('pwa.ticket.scanner_done');
    }

    public function set_attendance_type(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        if ($ticket->type == "buy"){
            $ticket->type = "done";
        }
        elseif ($ticket->type == "done") {
            $ticket->type = "buy";
        }
        $ticket->save();
        // dd($ticket);
        return response()->json(['success' => 'Success']);
    }
}
