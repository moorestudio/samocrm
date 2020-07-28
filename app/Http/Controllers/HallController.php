<?php

namespace App\Http\Controllers;

use App\Event;
use App\Hall;
use App\OutHall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
class HallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $event = Event::find($id);
        $row = Hall::where('event_id', $id)->count();
        $column = Hall::where('event_id', $id)->first();
        $halls = Hall::where('event_id', $id)->get();
        $out_halls = OutHall::where('event_id', $id)->get();
        $outrow = OutHall::where('event_id', $id)->count();
        $outcolumn = OutHall::where('event_id', $id)->first();
        $img_x = null;
        $img_y = null;
// dd(explode("#", $event->speaker_position));

        
        if ($column) {
            $out_width = OutHall::where('event_id', $id)->first()->width;
            $out_height = OutHall::where('event_id', $id)->first()->height;
            $collect = collect($column->column);
            $count = $collect->count();
            if($event->speaker_position){
                $img_x = explode("#", $event->speaker_position)[0];
                $img_y = explode("#", $event->speaker_position)[1];                
            }
            $outcollect = collect($outcolumn->column);
            $outcount = $outcollect->count();
        } else {
            $count = null;
            $width = null;
            $height = null;
            $outcount = null;
            $out_width=null;
            $out_height = null;

        }
        $data = [
            'event' => $event,
            'row' => $row,
            'column' => $count,
            'halls' => $halls,
            'out_width' => $out_width,
            'out_height' => $out_height,
            'out_halls' => $out_halls,
            'outrow' => $outrow,
            'outcolumn' => $outcount,
            'img_x' => $img_x,
            'img_y' => $img_y
        ];
        // dd($data);
        return view('admin.hall.create', $data);
    }

    public function store(Request $request)
    {
        $row = $request->input('row');
        $column = $request->input('column');
        for ($i = 1; $i <= $row; $i++) {
            $hall = new Hall();
            $hall->row = $i;
            $hall->event_id = $request->input('event_id');
            $arr = [];
            for ($y = 1; $y <= $column; $y++) {
                $arr[$y] = ['active' => 0, 'status' => -1,'shape' => ''];
            }
            $hall->column = $arr;

            $hall->width = $column;
            $hall->height = $row;

            $hall->save();
        }
///////////////////////////////////////////////////////////////////////////
        $row_out = $row+4;
        $column_out = $column+5;
        // dd($row_out);
        for ($ii = 1; $ii <= $row_out; $ii++) {
            $outhall = new OutHall();
            $outhall->row = $ii;
            $outhall->event_id = $request->input('event_id');
            $outarr = [];
            for ($yy = 1; $yy <= $column_out; $yy++) {
                $outarr[$yy] = ['active' => 0, 'status' => 0,'shape' => ''];
            }
            $outhall->column = $outarr;
            $outhall->width = $column_out;
            $outhall->height = $row_out;
            // dd($outhall);
            $outhall->save();
        }

///////////////////////////////////////////////////////////////////////////

        $event = Event::find($request->input('event_id'));

        return redirect()->route('hall.create', ['id' => $event->id]);
    }

    public function destroy(Request $request)
    {
        $row = Input::get('row');
        $column = Input::get('column');
        $row_arr = explode(",", $row[0]);
        $col_arr = explode(",", $column[0]);
        // dd($row_arr, $col_arr);
        $eventId = Input::get('eventId');
        for ($i = 0; $i < count($row_arr); $i++) {
            $hall = Hall::where('event_id', $eventId)->where('row', $row_arr[$i])->first();
            $var = collect($hall->column);
            $temp = $var[$col_arr[$i]];
            $temp['active'] = 1;
            $var[$col_arr[$i]] = $temp;
            $hall->column = collect($var);
            $hall->save();
        }

        return redirect()->back();
    }

    public function destroyAll(Request $request)
    {
        $event = $request->input('eventId');
        $halls = Hall::where('event_id', $event)->get();
        $outhalls = OutHall::where('event_id', $event)->get();
        foreach ($halls as $hall) {
            $hall->delete();
        }
        foreach ($outhalls as $hall) {
            $hall->delete();
        }
        return redirect()->back();
    }

    //type = 1 standart  type = 2 gold  type = 3 vip
    public function setType(Request $request)
    {
        set_time_limit(300);
        $row_arr = [];
        $col_arr = [];
        $status = $request->status;
        $eventId = $request->eventId;

        // $all_data = $request['all_data'];
        $all_data_str = $request->all_data;
        $all_data = explode(',', $all_data_str);
        // dd($all_data);
        foreach ($all_data as $row_col){
            $row_col_cleared = explode('-', $row_col);
            // dd($row_col_cleared);
            array_push($row_arr, $row_col_cleared[0]);
            array_push($col_arr, $row_col_cleared[1]);
        }      
       
        for ($i = 0; $i < count($row_arr); $i++) {
            $hall = Hall::where('event_id', $eventId)->where('row', $row_arr[$i])->first();
            $var = collect($hall->column);
            $temp = $var[$col_arr[$i]];
            $temp['status'] = $status;
            $var[$col_arr[$i]] = $temp;
            $hall->column = collect($var);
            $hall->save();
        }
        // return response()->json(['request'=>'$test']);
        return Redirect::back()->with('message','Operation Successful !');

    }

///////////
    public function setShape(Request $request)
    {
        // dd($request);
        set_time_limit(300);
        $row_arr = [];
        $col_arr = [];
        $eventId = $request->eventId;
        $status = $request->status;
        $shape = $request->shape;
        $all_data_str = $request->all_data;
        $all_data = explode(',', $all_data_str);
        foreach ($all_data as $row_col){
            $row_col_cleared = explode('-', $row_col);
            array_push($row_arr, $row_col_cleared[0]);
            array_push($col_arr, $row_col_cleared[1]);
        }      
       
        // dd($row_arr);
        for ($i = 0; $i < count($row_arr); $i++) {
            $hall = Hall::where('event_id', $eventId)->where('row', $row_arr[$i])->first();

            $var = collect($hall->column);
            $temp = $var[$col_arr[$i]];
            $temp['shape'] = $shape;
            if ($status){
                $temp['status'] = $status;
            }
            $var[$col_arr[$i]] = $temp;
            $hall->column = collect($var);
            $hall->save();
        }
        // return response()->json(['request'=>'$test']);
        return Redirect::back()->with('message','Operation Successful !');
        
    }
///////////////////////////////////////////////////////////////////////////////
    public function setSpeakerPosition(Request $request)
    {
        // dd($request);
        $x = $request->speaker_pos_x ? $request->speaker_pos_x :0;
        $y = $request->speaker_pos_y ? $request->speaker_pos_y :0;
        $pos = $x . "#" . $y;
        $eventId = $request->eventId;
        $event = Event::find($eventId);
        $event->speaker_position = $pos;
        $event->save();
        return Redirect::back()->with('message','Operation Successful !');
        
    }    
///////////////////////////////////////////////////////////////////////////////

    public function setShapeOut(Request $request)
    {
        // dd($request);
        set_time_limit(300);
        $row_arr = [];
        $col_arr = [];
        $eventId = $request->eventId;
        $status = $request->status;
        $shape = $request->shape;
        $all_data_str = $request->all_data;
        $all_data = explode(',', $all_data_str);
        foreach ($all_data as $row_col){
            $row_col_cleared = explode('-', $row_col);
            array_push($row_arr, $row_col_cleared[1]);
            array_push($col_arr, $row_col_cleared[2]);
        }      
       
        // dd($row_arr);
        for ($i = 0; $i < count($row_arr); $i++) {
            $hall = OutHall::where('event_id', $eventId)->where('row', $row_arr[$i])->first();
            // dd($hall);
            $var = collect($hall->column);
            $temp = $var[$col_arr[$i]];
            $temp['shape'] = $shape;
            if ($status){
                $temp['status'] = $status;
            }
            $var[$col_arr[$i]] = $temp;
            $hall->column = collect($var);
            $hall->save();
        }
        // return response()->json(['request'=>'$test']);
        return Redirect::back()->with('message','Operation Successful !');
        
    }

///////////////////////////////////////////////////////////////////////////////    




    public function deleteRow(Request $request)
    {
        $row = Input::get('row');
        $eventId = Input::get('eventId');
        $hall = Hall::where('event_id', $eventId)->where('row', $row)->first();
        $hall->delete();
        $halls = Hall::where('event_id', $eventId)->get();
        foreach ($halls as $hall) {
            $var = collect($hall->column);
            $values = $var->values();
            $New_start_index = 1;
            $arr = array_combine(range($New_start_index,
                count($values) + ($New_start_index - 1)),
                array_values($values->all()));
            $hall->column = collect($arr);
            $hall->save();
        }
        return response()->json(['success' => 'Success']);
    }

    public function deleteColumn(Request $request)
    {
        $columnId = Input::get('column');
        $eventId = Input::get('eventId');
        $halls = Hall::where('event_id', $eventId)->get();
        foreach ($halls as $hall) {
            $var = collect($hall->column);
            $var->pull($columnId);
            $values = $var->values();
            $New_start_index = 1;
            $arr = array_combine(range($New_start_index,
                count($values) + ($New_start_index - 1)),
                array_values($values->all()));
            $hall->column = collect($arr);
            $hall->save();
        }
        return response()->json(['success' => 'Success']);
    }
}
