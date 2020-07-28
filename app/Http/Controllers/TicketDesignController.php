<?php

namespace App\Http\Controllers;

use App\Mail\Pdfsend;
use App\Ticket;
use BaconQrCode\Encoder\QrCode;
use Barryvdh\DomPDF\Facade as PDF;
use App\Event;
use App\TicketDesign;
use Grpc\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\User;


class TicketDesignController extends Controller
{
    public function index()
    {

    }

    public function create(Request $request)
    {
        $event = Event::find($request->event);
        $ticketDesign = TicketDesign::where('event_id',$request->event)->first();

        return view('admin.ticket.create', ['event' => $event, 'ticketDesign' => $ticketDesign]);
    }

    public function store(Request $request)
    {
        $ticketDesign = TicketDesign::where('event_id',$request->id)->first();
        if(!$ticketDesign)
        {
            $ticketDesign = new TicketDesign();
        }
        $title_style = collect(['content' => $request->title_content, 'font_size' => $request->title_font_size, 'font_weight' => $request->title_font_weight, 'padding_bottom' => $request->title_padding_bottom ]);
        $address_style = collect(['content' => $request->address_content, 'font_size' => $request->address_font_size, 'font_weight' => $request->address_font_weight, 'padding_bottom' => $request->address_padding_bottom ]);
        $date_style = collect(['content' => $request->date_content, 'font_size' => $request->date_font_size, 'font_weight' => $request->date_font_weight, 'padding_bottom' => $request->date_padding_bottom ]);
        $city_style = collect(['content' => $request->city_content, 'font_size' => $request->city_font_size, 'font_weight' => $request->city_font_weight, 'padding_bottom' => $request->city_padding_bottom ]);
        $price_style = collect(['font_size' => $request->price_font_size, 'font_weight' => $request->price_font_weight, 'padding_bottom' => $request->price_padding_bottom ]);
        $image_style = collect(['width' => $request->image_width, 'height' => $request->image_height]);
        $places_style = collect(['font_size' => $request->places_font_size, 'font_weight' => $request->places_font_weight, 'padding_bottom' => $request->places_padding_bottom ]);

        $ticketDesign->title_style = $title_style;
        $ticketDesign->address_style = $address_style;
        $ticketDesign->date_style = $date_style;
        $ticketDesign->city_style = $city_style;
        $ticketDesign->price_style = $price_style;
        $ticketDesign->image_style = $image_style;
        $ticketDesign->places_style = $places_style;
        $ticketDesign->event_id = $request->id;
        $ticketDesign->text_block = $request->ticket_text_block;

        $ticketDesign->save();

        return response()->json([
           'status' => 'success',
        ]);
    }


    public function show($id){

        $ticket = Ticket::find($id);
        $user = User::find($ticket->user_id);

        $ticketDesign = TicketDesign::where('event_id','=',$ticket->event_id)->first();

        $event = Event::find($ticket->event_id);



        if($ticket->link ==null){
            $image = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(200)->errorCorrection('H')
                // ->generate('http://'.$_SERVER['SERVER_NAME'].'.kg');
                ->generate('ticket'.$ticket->id);
            $output_file = 'img-' . time() . '.png';
            Storage::put('public/local/'. $output_file, $image);
            $link = ('storage/local/'. $output_file);
            $ticket->link = $link;
        }

        return view('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->link, 'user' => $user]);
    }
    public function download(){
        $ticket = Ticket::find(28);
        $user = User::find($ticket->user_id);

        $ticketDesign = TicketDesign::where('event_id','=',$ticket->event_id)->first();

        $event = Event::find($ticket->event_id);
        $image = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(200)->errorCorrection('H')
            // ->generate('http://'.$_SERVER['SERVER_NAME'].'.kg');
            ->generate('ticket'.$ticket->id);
        $output_file = 'img-' . time() . '.png';
        Storage::put('public/local/'. $output_file, $image);
        $link = ('storage/local/'. $output_file);
        $view = view('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $link, 'user' => $user])->render();
        Browsershot::html($view)->save('storage/ticketpdf/images.pdf');
    }
}
