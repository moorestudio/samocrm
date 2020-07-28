<?php
namespace App\Exports;

use App\User;
use App\Ticket;
use App\Event;
use App\Certificate;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class AllAttendanceExport implements FromCollection,WithEvents,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $start;
    protected $end;

    public function __construct($start,$end){
      $this->start = $start;
      $this->end = $end;
    }

    public function collection()
    {
        $events = Event::all();
        if($this->start){
          $start = Carbon::createFromFormat('D M d Y H:i:s e+',$this->start)->format('Y-m-d');
          $end = Carbon::createFromFormat('D M d Y H:i:s e+',$this->end)->format('Y-m-d');
          foreach($events as $key=>$event){
            $date = Carbon::parseFromLocale($event->date,'ru')->format('Y-m-d');
            if($start <= $date and $end >= $date){
              continue;
            }else{
              $events->forget($key);
            }
          }
        }
        $data = array(
          array('Проект','Дата начало','Дата конец','Общ.кол билетов','Продано билетов','Выручка','Не продано билетов','Спикер','Город', 'Адрес'),
        );
        foreach($events as $event){
          $tickets = Ticket::where('event_id',$event->id)->get();
          $all_ticket = intval($event->client_count);
          $buy_ticket = count($tickets->whereIn('type',['done','buy']));
          $price = $tickets->whereIn('type',['done','buy'])->sum('ticket_price');
          array_push($data,array($event->title,$event->date,$event->end_date,$all_ticket,$buy_ticket,$price,$all_ticket - $buy_ticket,$event->mentor, $event->city, $event->address));
        }

        return collect($data);
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
        ];
    }
     public function registerEvents(): array
    {

        $styleArray = [
        'font' => [
        'bold' => true,
        ]
        ];

        return [
            AfterSheet::class    => function(AfterSheet $event) use ($styleArray)
            {
                $cellRange = 'A1:K1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

}
